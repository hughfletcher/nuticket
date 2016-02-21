<?php

return [
	'Tickets' => [
		'children' => [
			'Tickets' => [
				'route' => 'tickets.index'
			],
			'Create Ticket' => [
				'route' => 'tickets.create'
			]
		]
	],

	'Reports' => [
		'permissions' => ['isStaff'],
		'url' => 'reports'
	],
    'settings.settings' => [
        'permissions' => ['isAdmin'],
        'children' => [
            'settings.system' => ['route' => ['settings.edit', 'system']],
            'settings.emails' => ['route' => ['settings.edit', 'emails']],
            'settings.notifications' => ['route' => ['settings.edit', 'notifications']]
        ]
    ],
    'system.system' => [
    	'permissions' => ['isAdmin'],
    	'children' => [
            'system.logs' => ['route' => ['system.logs.index']],
        ]
    ]
];
