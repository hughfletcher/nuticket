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
            'settings.system' => [
                'url' => 'settings/system',
            ]
        ]
    ]
];
