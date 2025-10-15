<?php

use Medpzl\Clubdata\Controller\ClubController;
use Medpzl\Clubdata\Hooks\Entrance;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die('Access denied.');

ExtensionUtility::configurePlugin(
    'Clubdata',
    'Pi1',
    [
        ClubController::class => 'list, detail, services, newsletterUpcoming, listHighlights, preview, listPress',
    ],
    // non-cacheable actions
    [
        ClubController::class => 'list, services, listArchive, carousel, listHelpers, saveHelpers',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['clubdata'] = Entrance::class;
