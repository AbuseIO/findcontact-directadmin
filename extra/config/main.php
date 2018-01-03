<?php

/*
 * Config override to use the findcontact-ripe
 */

return [
    'external' => [
        'prefer_local'                      => true,
        'findcontact'                       => [
            'id' => [
                [
                    'class'                     => 'Directadmin',
                    'method'                    => 'getContactById',
                ],
            ],
            'ip' => [
                [
                    'class'                     => 'Directadmin',
                    'method'                    => 'getContactByIp',
                ],
            ],
            'domain' => [
                [
                    'class'                     => 'Directadmin',
                    'method'                    => 'getContactByDomain',
                ],
            ],
        ],
    ],
];
