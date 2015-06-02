<?php

namespace Reliv\RcmAxosoft\Log;

use Reliv\AxosoftApi\Model\GenericApiRequest;
use Reliv\AxosoftApi\ModelInterface\ApiError;
use Reliv\AxosoftApi\V5\ApiCreate\AbstractApiRequestCreate;
use Reliv\RcmAxosoft\Exception\AxosoftLoggerException;

require_once(__DIR__ . '/../autoload.php');

/**
 * Class AxosoftLoggerTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmAxosoft\Log
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AxosoftLoggerTest extends \PHPUnit_Framework_TestCase
{
    public $testCase
        = [
            // create
            '_default' => [
                'existingResponse' => [
                    'type' => 'ApiResponse',
                    'message' => 'TEST MESSAGE',
                    'data' => [],
                ],
                'commentResponse' => [
                    'type' => 'ApiResponse',
                    'message' => 'TEST MESSAGE',
                    'data' => [],
                ],
                'createResponse' => [
                    'type' => 'ApiResponse',
                    'message' => 'TEST MESSAGE',
                    'data' => [],
                ],
            ],
            // update
            'update' => [
                'existingResponse' => [
                    'type' => 'ApiResponse',
                    'message' => 'TEST MESSAGE',
                    'data' => [
                        [
                            'id' => '123',
                            'item_type' => 'desc',
                            'description' => 'TEST DESC',
                            'status' => [
                                'name' => 'defect'
                            ],
                        ],
                    ],
                ],
                'commentResponse' => [
                    'type' => 'ApiResponse',
                    'message' => 'TEST MESSAGE',
                    'data' => [],
                ],
                'createResponse' => [
                    'type' => 'ApiResponse',
                    'message' => 'TEST MESSAGE',
                    'data' => [],
                ],
            ],
            // createexistingerror
            'createexistingerror' => [
                'existingResponse' => [
                    'type' => 'ApiError',
                    'message' => 'TEST Error',
                ],
                'commentResponse' => [
                    'type' => 'ApiResponse',
                    'message' => 'TEST MESSAGE',
                    'data' => [],
                ],
                'createResponse' => [
                    'type' => 'ApiResponse',
                    'message' => 'TEST MESSAGE',
                    'data' => [],
                ],
            ],
            // createerror
            'createerror' => [
                'existingResponse' => [
                    'type' => 'ApiResponse',
                    'message' => 'TEST MESSAGE',
                    'data' => [],
                ],
                'commentResponse' => [
                    'type' => 'ApiError',
                    'message' => 'TEST Error',
                ],
                'createResponse' => [
                    'type' => 'ApiError',
                    'message' => 'TEST Error',
                ],
            ],
            // updateerror
            'updateerror' => [
                'existingResponse' => [
                    'type' => 'ApiResponse',
                    'message' => 'TEST MESSAGE',
                    'data' => [
                        [
                            'id' => '123',
                            'item_type' => 'desc',
                            'description' => 'TEST DESC',
                            'status' => [
                                'name' => 'defect'
                            ],
                        ],
                    ],
                ],
                'commentResponse' => [
                    'type' => 'ApiError',
                    'message' => 'TEST Error',
                ],
                'createResponse' => [
                    'type' => 'ApiError',
                    'message' => 'TEST Error',
                ],
            ],
        ];

    public function getApiResponse($message, $data)
    {
        $mockApiResponse = $this->getMockBuilder(
            '\Reliv\AxosoftApi\V5\ApiList\ApiResponse'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockApiResponse->expects($this->any())
            ->method('getMessage')
            ->will(
                $this->returnValue($message)
            );

        $mockApiResponse->expects($this->any())
            ->method('getData')
            ->will(
                $this->returnValue($data)
            );

        return $mockApiResponse;
    }

    public function getApiError($message)
    {
        $mockApiError = $this->getMockBuilder(
            '\Reliv\AxosoftApi\ModelInterface\ApiError'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockApiError->expects($this->any())
            ->method('getMessage')
            ->will(
                $this->returnValue($message)
            );

        return $mockApiError;
    }

    public function getResponse($responseTestData)
    {
        if ($responseTestData['type'] == 'ApiError') {
            return $this->getApiError(
                $responseTestData['message']
            );
        }

        return $this->getApiResponse(
            $responseTestData['message'],
            $responseTestData['data']
        );
    }

    /**
     * getMockAxosoftApi
     *
     * @return \PHPUnit_Framework_MockObject_MockObject | \Reliv\AxosoftApi\ModelInterface\ApiResponse
     */
    public function getMockAxosoftApi($testCase = '_default')
    {
        $testCaseData = $this->testCase[$testCase];

        $self = $this;

        $sendCallback = function () use ($self, $testCaseData) {
            $args = func_get_args();

            if ($args[0] instanceof \Reliv\AxosoftApi\V5\Items\ApiRequestList) {
                return $self->getResponse($testCaseData['existingResponse']);
            }

            if ($args[0] instanceof GenericApiRequest) {
                return $self->getResponse($testCaseData['commentResponse']);
            }

            if ($args[0] instanceof AbstractApiRequestCreate) {
                return $self->getResponse($testCaseData['createResponse']);
            }

            throw new \Exception('Nope');
        };

        $hasErrorCallback = function () {
            $args = func_get_args();

            if ($args[0] instanceof ApiError) {
                return true;
            }

            return false;
        };

        $mockAxosoftApi = $this->getMockBuilder(
            '\Reliv\AxosoftApi\Service\AxosoftApi'
        )
            ->disableOriginalConstructor()
            ->getMock();


        $mockAxosoftApi->expects($this->any())
            ->method('send')
            ->will(
                $this->returnCallback($sendCallback)
            );

        $mockAxosoftApi->expects($this->any())
            ->method('hasError')
            ->will(
                $this->returnCallback($hasErrorCallback)
            );

        return $mockAxosoftApi;
    }

    /**
     * testCase1
     *
     * @return void
     */
    public function testCase1()
    {
        $mockAxosoftApi = $this->getMockAxosoftApi('_default');
        $loggerOptions = [
            'tryResubmitTimeout' => 0,
        ];

        $unit = new AxosoftLogger($mockAxosoftApi, $loggerOptions);

        $this->assertInstanceOf(
            '\Reliv\RcmAxosoft\Log\AxosoftLogger',
            $unit->log(1, 'TEST_MSG', [])
        );

        // double submit test
        $unit->log(1, 'TEST_MSG', []);
    }

    /**
     * testCase2
     *
     * @return void
     */
    public function testCase2()
    {
        $mockAxosoftApi = $this->getMockAxosoftApi('_default');
        $loggerOptions = [
            'tryResubmitTimeout' => 10,
        ];

        $unit = new AxosoftLogger($mockAxosoftApi, $loggerOptions);

        $this->assertInstanceOf(
            '\Reliv\RcmAxosoft\Log\AxosoftLogger',
            $unit->log(1, 'TEST_MSG', [])
        );

        // double submit test
        $unit->log(1, 'TEST_MSG', []);
    }
    /**
     * testCase22
     *
     * @return void
     */
    public function testCase22()
    {
        $mockAxosoftApi = $this->getMockAxosoftApi('update');
        $loggerOptions = [
            'tryResubmitTimeout' => 10,
            'enterIssueIfNotStatus' => ['defect'],
        ];

        $unit = new AxosoftLogger($mockAxosoftApi, $loggerOptions);

        $this->assertInstanceOf(
            '\Reliv\RcmAxosoft\Log\AxosoftLogger',
            $unit->log(1, 'TEST_MSG', [])
        );

        // double submit test
        $unit->log(1, 'TEST_MSG', []);
    }
    /**
     * testCase3
     *
     * @return void
     */
    public function testCase3()
    {
        $mockAxosoftApi = $this->getMockAxosoftApi('update');
        $loggerOptions = [

        ];

        $unit = new AxosoftLogger($mockAxosoftApi, $loggerOptions);

        $this->assertInstanceOf(
            '\Reliv\RcmAxosoft\Log\AxosoftLogger',
            $unit->log(1, 'TEST_MSG', [])
        );
    }

    /**
     * testCase4
     *
     * @return void
     */
    public function testCase4()
    {
        $mockAxosoftApi = $this->getMockAxosoftApi('createerror');
        $loggerOptions = [

        ];

        $unit = new AxosoftLogger($mockAxosoftApi, $loggerOptions);

        $hasError = false;

        try {
            $unit->log(1, 'TEST_MSG', []);
        } catch (AxosoftLoggerException $exception) {
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }

    /**
     * testCase5
     *
     * @return void
     */
    public function testCase5()
    {
        $mockAxosoftApi = $this->getMockAxosoftApi('updateerror');
        $loggerOptions = [

        ];

        $unit = new AxosoftLogger($mockAxosoftApi, $loggerOptions);

        $hasError = false;

        try {
            $unit->log(1, 'TEST_MSG', []);
        } catch (AxosoftLoggerException $exception) {
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }

    /**
     * testCase6
     *
     * @return void
     */
    public function testCase6()
    {
        $mockAxosoftApi = $this->getMockAxosoftApi('createexistingerror');
        $loggerOptions = [

        ];

        $unit = new AxosoftLogger($mockAxosoftApi, $loggerOptions);

        $hasError = false;

        try {
            $unit->log(1, 'TEST_MSG', []);
        } catch (AxosoftLoggerException $exception) {
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }
}
