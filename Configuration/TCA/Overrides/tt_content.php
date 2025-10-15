<?php

defined('TYPO3') || die('Access denied.');

// CType for the new plugin registration method
$cType = 'clubdata_pi1';

// Register the plugin as a content element (CType)
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Clubdata',
    'Pi1',
    'Club Data',
    'clubdata-plugin',
    'special'
);

// Configure the TCA for the new CType
$GLOBALS['TCA']['tt_content']['types'][$cType] = [
    'showitem' => '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;header,
            pi_flexform,
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
    ',
];

// Add FlexForm configuration for the plugin
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:clubdata/Configuration/FlexForm/Config.xml',
    $cType
);
