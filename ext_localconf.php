<?php

use Medpzl\Clubdata\Controller\ClubController;
use Medpzl\Clubdata\Hooks\Entrance;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die('Access denied.');

// Automatically set entrance on save
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['clubdata'] = Entrance::class;

// List view plugin
ExtensionUtility::configurePlugin(
    'Clubdata',
    'List',
    [
        ClubController::class => 'list',
    ],
    [
        ClubController::class => 'list',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

// Detail view plugin
ExtensionUtility::configurePlugin(
    'Clubdata',
    'Detail',
    [
        ClubController::class => 'detail',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

// Services plugin
ExtensionUtility::configurePlugin(
    'Clubdata',
    'Services',
    [
        ClubController::class => 'services',
    ],
    [
        ClubController::class => 'services',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

// Archive list plugin
ExtensionUtility::configurePlugin(
    'Clubdata',
    'ListArchive',
    [
        ClubController::class => 'listArchive',
    ],
    [
        ClubController::class => 'listArchive',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

// Upcoming plugin
ExtensionUtility::configurePlugin(
    'Clubdata',
    'Upcoming',
    [
        ClubController::class => 'upcoming',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

// Highlights plugin
ExtensionUtility::configurePlugin(
    'Clubdata',
    'ListHighlights',
    [
        ClubController::class => 'listHighlights',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

// Preview plugin
ExtensionUtility::configurePlugin(
    'Clubdata',
    'Preview',
    [
        ClubController::class => 'preview',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

// Carousel plugin
ExtensionUtility::configurePlugin(
    'Clubdata',
    'Carousel',
    [
        ClubController::class => 'carousel',
    ],
    [
        ClubController::class => 'carousel',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

// Press plugin
ExtensionUtility::configurePlugin(
    'Clubdata',
    'ListPress',
    [
        ClubController::class => 'listPress',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

// Helpers plugin
ExtensionUtility::configurePlugin(
    'Clubdata',
    'ListHelpers',
    [
        ClubController::class => 'listHelpers, saveHelpers',
    ],
    [
        ClubController::class => 'listHelpers, saveHelpers',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
