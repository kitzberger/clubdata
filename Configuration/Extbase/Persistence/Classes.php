<?php

declare(strict_types=1);

return [
    \Medpzl\Clubdata\Domain\Model\Category::class => [
        'tableName' => 'sys_category',
    ],
    \Medpzl\Clubdata\Domain\Model\FrontendUser::class => [
        'tableName' => 'fe_users',
        'properties' => [
            'username' => [
                'fieldName' => 'username',
            ],
            'firstName' => [
                'fieldName' => 'first_name',
            ],
            'lastName' => [
                'fieldName' => 'last_name',
            ],
            'address' => [
                'fieldName' => 'address',
            ],
            'email' => [
                'fieldName' => 'email',
            ],
            'title' => [
                'fieldName' => 'title',
            ],
            'salutation' => [
                'fieldName' => 'salutation',
            ],
            'company' => [
                'fieldName' => 'company',
            ],
            'zip' => [
                'fieldName' => 'zip',
            ],
            'city' => [
                'fieldName' => 'city',
            ],
            'telephone' => [
                'fieldName' => 'telephone',
            ],
            'usergroup' => [
                'fieldName' => 'usergroup',
            ],
        ],
    ],
    \Medpzl\Clubdata\Domain\Model\FrontendUserGroup::class => [
        'tableName' => 'fe_groups',
        'properties' => [
            'title' => [
                'fieldName' => 'title',
            ],
            'description' => [
                'fieldName' => 'description',
            ],
        ],
    ],
    \Medpzl\Clubdata\Domain\Model\ProgramServiceRel::class => [
        'tableName' => 'tx_clubdata_domain_model_programservicerel',
        'properties' => [
            'sorting' => [
                'fieldName' => 'sorting',
            ],
        ],
    ],
];
