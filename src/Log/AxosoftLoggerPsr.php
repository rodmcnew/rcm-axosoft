<?php

namespace Reliv\RcmAxosoft\Log;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Reliv\AxosoftApi\Model\ApiError;
use Reliv\AxosoftApi\Model\GenericApiRequest;
use Reliv\AxosoftApi\Service\AxosoftApi;
use Reliv\AxosoftApi\V5\ApiCreate\AbstractApiRequestCreate;
use Reliv\AxosoftApi\V5\Items\ApiRequestList;
use Reliv\AxosoftApi\V5\Items\ApiResponseList;
use Reliv\RcmAxosoft\Exception\AxosoftLoggerException;
use Reliv\RcmAxosoft\LogPrepare\DescriptionFromLog;
use Reliv\RcmAxosoft\LogPrepare\SummaryFromLog;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AxosoftLoggerPsr extends AbstractLogger implements LoggerInterface
{
    /**
     * [
     *  'itemType' => 'defect', // Bug
     *  'tryResubmitTimeout' => 5,
     *  'projectIdToCheckForIssues' => 0,
     *  'enterIssueIfNotStatus' => [],
     *  'projectId' => 0,
     *  'releaseId' => null,
     * ]
     *
     * @var array $options
     */
    protected $options = [];

    /**
     * @var AxosoftApi $api
     */
    protected $api = null;

    /**
     * @var DescriptionFromLog
     */
    protected $descriptionFromLog;

    /**
     * @var SummaryFromLog
     */
    protected $summaryFromLog;

    /**
     * @var array Track the submitted items 'Summary' => DateTime
     * - NOTE: This may cause memory issues for long running processes
     */
    protected $submitted = [];

    /**
     * @param AxosoftApi         $api
     * @param DescriptionFromLog $descriptionFromLog
     * @param SummaryFromLog     $summaryFromLog
     * @param array              $options
     */
    public function __construct(
        AxosoftApi $api,
        DescriptionFromLog $descriptionFromLog,
        SummaryFromLog $summaryFromLog,
        array $options = []
    ) {
        $this->api = $api;
        $this->options = array_merge($this->options, $options);
        $this->descriptionFromLog = $descriptionFromLog;
        $this->summaryFromLog = $summaryFromLog;
    }

    /**
     * log
     *
     * @param int   $priority
     * @param mixed $message
     * @param array $extra
     *
     * @return $this
     */
    public function log($priority, $message, array $extra = [])
    {
        $summary = $this->summaryFromLog->__invoke(
            $priority,
            $message,
            $extra,
            $this->options
        );

        $existingItem = $this->getExistingItem($summary);

        if ($existingItem) {
            // Add comment
            $this->addComment($existingItem, $summary, $extra);

            return $this;
        }

        if (!$this->canCreate($summary)) {
            return $this;
        }

        $description = $this->descriptionFromLog->__invoke(
            $priority,
            $message,
            $extra,
            $this->options
        );

        // create issue
        $this->createIssue($summary, $description);

        return $this;
    }

    /**
     * @param      $key
     * @param null $default
     *
     * @return mixed|null
     */
    protected function getOption($key, $default = null)
    {
        if (array_key_exists($key, $this->options)) {
            return $this->options[$key];
        }

        return $default;
    }

    /**
     * getApi
     *
     * @return \Reliv\AxosoftApi\Service\AxosoftApi|null
     */
    protected function getApi()
    {
        return $this->api;
    }

    /**
     * getItemObject
     *
     * @return AbstractApiRequestCreate
     */
    protected function getItemObject()
    {
        $itemType = $this->getOption('itemType', 'defect');

        return ItemTypeCreateMap::getItemObject($itemType);
    }

    /**
     * addSubmitted
     *
     * @param $summary
     *
     * @return void
     */
    protected function addSubmitted($summary)
    {
        $this->submitted[$summary] = new \DateTime();
    }

    /**
     * removeSubmitted
     *
     * @param $summary
     *
     * @return void
     */
    protected function removeSubmitted($summary)
    {
        unset($this->submitted[$summary]);
    }

    /**
     * getSubmittedTime
     *
     * @param $summary
     *
     * @return null|\DateTime
     */
    protected function getSubmittedTime($summary)
    {
        if (isset($this->submitted[$summary])) {
            return $this->submitted[$summary];
        }

        return null;
    }

    /**
     * canCreate
     *
     * @return bool
     */
    protected function canCreate($summary)
    {
        $existing = $this->getSubmittedTime($summary);

        if ($existing === null) {
            return true;
        }

        $tryResubmitTimeout = $this->getOption('tryResubmitTimeout', 5);

        $now = new \DateTime();

        $diff = $now->getTimestamp() - $existing->getTimestamp();

        if ($diff >= $tryResubmitTimeout) {
            $this->removeSubmitted($summary);

            return true;
        }

        return false;
    }

    /**
     * getExistingItem
     *
     * @param $summary
     *
     * @return mixed
     * @throws AxosoftLoggerException
     */
    protected function getExistingItem($summary)
    {
        $api = $this->getApi();

        $request = new ApiRequestList();
        $request->setProjectId($this->getOption('projectIdToCheckForIssues', 0));
        $request->setSearchString($this->prepareSearchString($summary));
        $request->setSearchField('name');
        $request->setSortFields('created_date_time');

        /** @var ApiError|ApiResponseList $response */
        $response = $api->send($request);

        if ($api->hasError($response)) {
            throw new AxosoftLoggerException(
                'Existing item search failed. '
                . $response->getMessage()
            );
        }

        $data = $response->getData();

        if (count($data) < 1) {
            return null;
        }

        $enterIssueIfNotStatus = $this->getOption('enterIssueIfNotStatus', []);

        $existingItem = null;

        foreach ($data as $item) {
            if (!in_array($item['status']['name'], $enterIssueIfNotStatus)) {
                // we return the first one we find
                return $item;
            }
        }

        return null;
    }

    /**
     * addComment
     *
     * @param       $existingItem
     * @param       $summary
     * @param array $extra
     *
     * @return void
     * @throws \Exception
     */
    protected function addComment($existingItem, $summary, $extra = [])
    {
        $updateData = [];
        $updateDate = new \DateTime();

        $updateData['notify_customer'] = false;
        $updateData['item'] = []; //$data[0];

        $updateData['item']['description'] = $existingItem['description']
            . "<br/>- Error occured again: "
            . $updateDate->format(\DateTime::W3C)
            . " " . $summary;

        //$updateData['item']['notes'] =
        // $existingItem['notes']
        // . "/n-This has been added on "
        // . $updateDate->format(\DateTime::W3C);

        $updateData['item']['id'] = $existingItem['id'];

        $updateUrl = '/api/v5/' . $existingItem['item_type']
            . '/' . $existingItem['id'];

        $request = new GenericApiRequest($updateUrl, 'POST', $updateData);

        $api = $this->getApi();

        /** @var ApiError|ApiResponseList $response */
        $response = $api->send($request);

        if ($api->hasError($response)) {
            throw new AxosoftLoggerException(
                'Could and comment to item. '
                . $response->getMessage()
            );
        }
    }

    /**
     * @param $summary
     * @param $description
     *
     * @return void
     * @throws AxosoftLoggerException
     */
    protected function createIssue($summary, $description)
    {
        // Add a new defect
        $request = $this->getItemObject();

        $request->setDescription($description);
        $request->setName($summary);
        $request->setProject($this->getOption('projectId', 0));

        $releaseId = $this->getOption('releaseId');
        if ($releaseId) {
            $request->setRelease($releaseId);
        }

        $api = $this->getApi();
        /** @var ApiError|ApiResponseList $response */
        $response = $api->send($request);

        if ($api->hasError($response)) {
            throw new AxosoftLoggerException(
                'Could not create item. '
                . $response->getMessage()
            );
        }

        $this->addSubmitted($summary);
    }

    /**
     * prepareSearchString
     *
     * @param $searchString
     *
     * @return string
     */
    protected function prepareSearchString($searchString)
    {
        // Add proper quotes
        return '"' . $searchString . '"';
    }
}
