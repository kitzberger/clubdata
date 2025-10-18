<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_program_service_user_mm',
        'label' => 'remark',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'versioningWS' => true,
        'hideTable' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'remark,program,service,user',
        'typeicon_classes' => [
            'default' => 'clubdata-programservice'
        ]
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, remark, program, service, user, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => ['type' => 'language'],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'foreign_table' => 'tx_clubdata_program_service_user_mm',
                'foreign_table_where' => 'AND tx_clubdata_program_service_user_mm.pid=###CURRENT_PID### AND tx_clubdata_program_service_user_mm.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.enabled'
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'default' => 0,
            ]
        ],
        'endtime' => [
            'exclude' => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ]
            ],
        ],
        'remark' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_program_service_user_mm.remark',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'program' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_program_service_user_mm.program',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_clubdata_domain_model_program',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'service' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_program_service_user_mm.service',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_clubdata_domain_model_service',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'user' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_program_service_user_mm.user',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_users',
                'foreign_table_where' => 'AND fe_users.usergroup like "%4%"',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
    ],
];
