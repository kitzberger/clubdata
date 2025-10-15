<?php

defined('TYPO3') || die('Access denied.');

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    // Main models
    'clubdata-program' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:clubdata/Resources/Public/Icons/SVG/program.svg',
    ],
    'clubdata-link' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:clubdata/Resources/Public/Icons/SVG/link.svg',
    ],
    'clubdata-service' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:clubdata/Resources/Public/Icons/SVG/service.svg',
    ],
    'clubdata-state' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:clubdata/Resources/Public/Icons/SVG/state.svg',
    ],
    'clubdata-seating' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:clubdata/Resources/Public/Icons/SVG/seating.svg',
    ],

    // Relation models
    'clubdata-programservice' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:clubdata/Resources/Public/Icons/SVG/programservice.svg',
    ],
    'clubdata-programlinkrel' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:clubdata/Resources/Public/Icons/SVG/programlinkrel.svg',
    ],
    'clubdata-programservicerel' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:clubdata/Resources/Public/Icons/SVG/programservicerel.svg',
    ],

    // Plugin icon (same as program for consistency)
    'clubdata-plugin' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:clubdata/Resources/Public/Icons/SVG/program.svg',
    ],

    // Backend module icon
    'module-clubdata-helpers' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:clubdata/Resources/Public/Icons/module_helpers.png',
    ],
];
