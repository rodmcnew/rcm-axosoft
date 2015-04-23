<?php
return [
    /**
     * Configuration for AXOSOFT LOGGER
     */
    'Reliv\RcmAxosoft' => [
        'errorLogger' => [
            // Bug
            // Issue will be entered in this project
            'projectId' => 0,

            // Check for existing open item in this project
            // 0 = ALL Projects
            'projectIdToCheckForIssues' => 0,

            // If we find and issue that is NOT in these statuses,
            // then we will open a new one
            'enterIssueIfNotStatus' => [
                'Closed' => 'Closed',
            ],

            // Include dump of server vars - true to include server dump
            'includeServerDump' => true,

            // WARNING: this can be a security issue
            // Set to an array of specific session keys to display or 'ALL' to display all
            'includeSessionVars' => false,

            // This is useful for preventing exceptions who have dynamic
            // parts from creating multiple entries
            // Descriptions will be run through preg_replace
            // using these as the preg_replace arguments.
            'summaryPreprocessors' => [
                // $pattern => $replacement
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            'Reliv\RcmAxosoft\Log\AxosoftLogger' => '\RcmAxosoft\Factory\AxosoftLoggerFactory',
        ]
    ],
];
