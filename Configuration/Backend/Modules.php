<?php

declare(strict_types=1);

use Medpzl\Clubdata\Controller\BackendController;

/**
 * Backend module configuration for TYPO3 13
 */
return [
    'cart_clubdata' => [
        'parent' => 'cart_cart_main',
        'position' => ['bottom'],
        'access' => 'user, group',
        'workspaces' => 'live',
        'iconIdentifier' => 'module-clubdata-helpers',
        'path' => '/module/cartcart/clubdata',
        'labels' => 'LLL:EXT:clubdata/Resources/Private/Language/locallang_db.xlf:tx_clubdata.module.clubdatahelpers',
        'extensionName' => 'Clubdata',
        'controllerActions' => [
            BackendController::class => [
                'listHelpers',
                'saveHelpers',
            ],
        ],
        'navigationComponentId' => 'typo3-pagetree',
    ],
];
