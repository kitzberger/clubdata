<?php

declare(strict_types=1);

defined('TYPO3') or die();

// Add children field to sys_category
$temporaryColumns = [
    'children' => [
        'label' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:sys_category.children',
        'config' => [
            'type' => 'inline',
            'foreign_table' => 'sys_category',
            'foreign_field' => 'parent',
            'foreign_sortby' => 'sorting',
            'maxitems' => 9999,
            'appearance' => [
                'collapseAll' => true,
                'expandSingle' => true,
                'levelLinksPosition' => 'top',
                'useSortable' => true,
                'showPossibleLocalizationRecords' => false,
                'showRemovedLocalizationRecords' => false,
                'showAllLocalizationLink' => false,
                'showSynchronizationLink' => false,
            ],
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'sys_category',
    $temporaryColumns
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'sys_category',
    'children',
    '',
    'after:parent'
);
