<?php

defined('TYPO3') || die('Access denied.');

// Plugin configurations
$plugins = [
    'List' => [
        'ctype' => 'clubdata_list',
        'title' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.list.title',
        'description' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.list.description',
        'flexform' => 'List.xml',
        'icon' => 'clubdata-plugin'
    ],
    'Detail' => [
        'ctype' => 'clubdata_detail',
        'title' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.detail.title',
        'description' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.detail.description',
        'flexform' => 'Detail.xml',
        'icon' => 'clubdata-plugin'
    ],
    'Services' => [
        'ctype' => 'clubdata_services',
        'title' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.services.title',
        'description' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.services.description',
        'flexform' => null,
        'icon' => 'clubdata-plugin'
    ],
    'ListArchive' => [
        'ctype' => 'clubdata_listarchive',
        'title' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.listarchive.title',
        'description' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.listarchive.description',
        'flexform' => 'ListArchive.xml',
        'icon' => 'clubdata-plugin'
    ],
    'Upcoming' => [
        'ctype' => 'clubdata_upcoming',
        'title' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.upcoming.title',
        'description' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.upcoming.description',
        'flexform' => 'Upcoming.xml',
        'icon' => 'clubdata-plugin'
    ],
    'ListHighlights' => [
        'ctype' => 'clubdata_listhighlights',
        'title' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.listhighlights.title',
        'description' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.listhighlights.description',
        'flexform' => 'ListHighlights.xml',
        'icon' => 'clubdata-plugin'
    ],
    'Preview' => [
        'ctype' => 'clubdata_preview',
        'title' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.preview.title',
        'description' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.preview.description',
        'flexform' => 'Preview.xml',
        'icon' => 'clubdata-plugin'
    ],
    'Carousel' => [
        'ctype' => 'clubdata_carousel',
        'title' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.carousel.title',
        'description' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.carousel.description',
        'flexform' => 'Carousel.xml',
        'icon' => 'clubdata-plugin'
    ],
    'ListPress' => [
        'ctype' => 'clubdata_listpress',
        'title' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.listpress.title',
        'description' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.listpress.description',
        'flexform' => 'ListPress.xml',
        'icon' => 'clubdata-plugin'
    ],
    'ListHelpers' => [
        'ctype' => 'clubdata_listhelpers',
        'title' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.listhelpers.title',
        'description' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:plugin.listhelpers.description',
        'flexform' => 'ListHelpers.xml',
        'icon' => 'clubdata-plugin'
    ],
];

// Register each plugin and configure TCA
foreach ($plugins as $pluginKey => $config) {
    $cType = $config['ctype'];
    
    // Register the plugin
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Clubdata',
        $pluginKey,
        $config['title'],
        $config['icon'],
        'special',
        $config['description']
    );
    
    // Configure the TCA showitem
    $showitem = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;header,';
    
    if ($config['flexform']) {
        $showitem .= '
            pi_flexform,';
    }
    
    $showitem .= '
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
    ';
    
    $GLOBALS['TCA']['tt_content']['types'][$cType] = [
        'showitem' => $showitem,
    ];
    
    // Add FlexForm if configured
    if ($config['flexform']) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:clubdata/Configuration/FlexForm/' . $config['flexform'],
            $cType
        );
    }
}
