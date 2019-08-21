<?php

/*
 * This file is part of the leonis/easysms-notification-channel.
 * (c) yangliulnn <yangliulnn@163.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
    'timeout' => 5.0,

    'default' => [
        'strategy' => \Override\Overtrue\EasySms\Strategies\OrderStrategy::class,

        'gateways' => [
            'local',
        ],
    ],

    'gateways' => [
        'local' => [
            'name' => 'local',
        ],
    ],

    'custom_gateways' => [
        'local' => \Override\Overtrue\EasySms\Gateways\LocalGateway::class,
    ],
];
