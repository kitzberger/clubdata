<?php

$ll = 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:';
return [
    'ctrl' => [
        'title' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program',
        'label' => 'title',
        'label_alt' => 'datetime',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'versioningWS' => true,
        'default_sortby' => 'datetime DESC',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,intern,avoid_nl,sub_title,sec_sub_title,datetime,hide_date,entrance,seating,venue,description,picture,highlight,perm_highlight,state,cat_price_a,price_a,cat_price_b,price_b,cat_price_c,price_c,pre_sales,ticket_link,internal_info,visitors,links,services,categories,reduction,genre,festival,noservice,slug',
        'typeicon_classes' => [
            'default' => 'clubdata-program'
        ]
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource,   hidden;;1,intern, datetime, title, sub_title, sec_sub_title, slug, festival, categories,genre, entrance, seating, seatings, venue,
         --div--;' . $ll . 'tx_clubdata_domain_model_program.tabs_tickets,
         state,state_text,max_tickets,sold_tickets,cat_price_a, price_a, cat_price_b, price_b, cat_price_c, price_c,reduction,pre_sales,  ticket_link,
             --div--;' . $ll . 'tx_clubdata_domain_model_program.tabs_info,
             description;;;richtext:rte_transform[mode=ts_links], picture,links,
             --div--;' . $ll . 'tx_clubdata_domain_model_program.tabs_advanced,
             highlight, perm_highlight, avoid_nl, hide_date,
              --div--;' . $ll . 'tx_clubdata_domain_model_program.tabs_services, noservice,service_bar_num,services,
              --div--;' . $ll . 'tx_clubdata_domain_model_program.tabs_internal,
              internal_info;;;richtext:rte_transform[mode=ts_links],visitors,  --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
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
                'foreign_table' => 'tx_clubdata_domain_model_program',
                'foreign_table_where' => 'AND tx_clubdata_domain_model_program.pid=###CURRENT_PID### AND tx_clubdata_domain_model_program.sys_language_uid IN (-1,0)',
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
            ]
        ],

        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
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
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
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
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],

        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'intern' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.intern',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'avoid_nl' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.avoid_nl',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'sub_title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.sub_title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'sec_sub_title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.sec_sub_title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'slug' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.slug',
            'config' => [
                'type' => 'slug',
                'size' => 50,
                'generatorOptions' => [
                    'fields' => ['title', 'datetime'],
                    'fieldSeparator' => '-',
                    'prefixParentPageSlug' => false,
                    'replacements' => [
                        '/' => '-',
                    ],
                    'postModifiers' => [
                        'Medpzl\Clubdata\Slug\ProgramDateModifier->modify'
                    ]
                ],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInSite',
                'default' => ''
            ],
        ],
        'datetime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.datetime',
            'config' => [
                'type' => 'datetime',
                'size' => 10,
                'default' => time()
            ],
        ],
        'hide_date' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.hide_date',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'entrance' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.entrance',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'enableRichtext' => true,
            ],
        ],
        'seating' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.seating',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'venue' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.venue',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'picture' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.picture',
            'config' => [
                'type' => 'file',
                'appearance' => [
                    'createNewRelationLinkTitle' => 'Add file reference'
                ],
                'allowed' => 'common-image-types',
                'maxitems' => 15,
                'foreign_types' => [
                    '0' => [
                        'showitem' => '
                        --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                        --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\FileType::TEXT->value => [
                        'showitem' => '
                        --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                        --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\FileType::IMAGE->value => [
                        'showitem' => '
                        --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                        --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\FileType::AUDIO->value => [
                        'showitem' => '
                        --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                        --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\FileType::VIDEO->value => [
                        'showitem' => '
                        --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                        --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\FileType::APPLICATION->value => [
                        'showitem' => '
                        --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                        --palette--;;filePalette'
                    ]
                ]
            ],
        ],
        'highlight' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.highlight',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'perm_highlight' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.perm_highlight',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'cat_price_a' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.cat_price_a',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'price_a' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.price_a',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'cat_price_b' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.cat_price_b',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'price_b' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.price_b',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'cat_price_c' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.cat_price_c',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'price_c' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.price_c',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'ticket_link' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.ticket_link',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'pre_sales' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.pre_sales',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'enableRichtext' => true,
            ],
        ],
        'internal_info' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.internal_info',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'enableRichtext' => true,
            ],
        ],
        'visitors' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.visitors',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'state' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.state',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_clubdata_domain_model_state',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'max_tickets' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.max_tickets',
            'config' => [
                'type' => 'number',
                'size' => 4
            ]
        ],
        'sold_tickets' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.sold_tickets',
            'config' => [
                'type' => 'number',
                'size' => 4
            ]
        ],
        'service_bar_num' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.service_bar_num',
            'config' => [
                'type' => 'number',
                'size' => 4
            ]
        ],
        'state_text' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.state_text',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'links' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.links',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_clubdata_program_link_mm',
                'foreign_field' => 'program',
                'foreign_label' => 'link',
                'foreign_unique' => 'link',
            ],
        ],
        'services' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.services',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_clubdata_program_service_user_mm',
                'foreign_field' => 'program',
                'foreign_label' => 'service',
                'foreign_unique' => 'service',
            ],
        ],
        'categories' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.categories',
            'config' => [
                'type' => 'category',
            ],
        ],
        'seatings' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.seatings',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_clubdata_domain_model_seating',
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'reduction' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.reduction',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 0,
            ]

        ],
        'festival' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.festival',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'noservice' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.noservice',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'genre' => [
            'exclude' => true,
            'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata_domain_model_program.genre',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
    ],
];
