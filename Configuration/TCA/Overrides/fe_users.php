<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

if (!isset($GLOBALS['TCA']['fe_users']['columns']['salutation'])) {
    $tca = [
        'columns' => [
            'salutation' => [
                'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:fe_users.salutation',
                'config' => [
                    'type' => 'input',
                    'size' => 40,
                    'eval' => 'trim',
                    'max' => 160,
                ],
            ],
        ],
    ];
    $GLOBALS['TCA']['fe_users'] = array_replace_recursive($GLOBALS['TCA']['fe_users'], $tca);
    ExtensionManagementUtility::addToAllTCAtypes('fe_users', 'salutation', '', 'before:title');
}
