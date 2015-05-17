<?php 

return [
	'Tickets' => [
		'public' => true,
		'children' => [
			'Tickets' => [
				'route' => 'tickets.index',
				'public' => true
			],
			'Create Ticket' => [
				'route' => 'tickets.create',
				'public' => true
			]
		] 
	],

	'Reports' => [
		'public' => false,
		'url' => 'reports'
	], 
	'Development' => [
		'public' => false,
		'url' => 'dev'
	]
];