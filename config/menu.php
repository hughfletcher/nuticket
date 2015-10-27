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
	]
];
