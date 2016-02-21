<?php

return [

    'title' => (string) 'NuTicket :: Ticket Support System',

	'time' => [
		'enabled' => (boolean) true,
		'edit' => (boolean) true
	],

	'default' => [
		'tz' => (string) 'America/Chicago',
		'dept' => (integer) 1,
		'pagesize' => (integer) 25,
		'priority' => (integer) 3,
		'org' => (integer) 1
 	],

	'format' => [
		'date' => (string) 'm/d/Y',
		'dateday' => (string) 'm/d/Y D',
        'datetime' => (string) 'm/d/Y g:i a'
	],

	'theme' => (string) 'adminlte',

	'log' => [
		'level' => (string) env('LOG_LEVEL', 'warning'),
	],

	//access
	'registration' => [
		'required' => (boolean) true,
		'method' => (string) 'false' //public,private,false
	],

	//mail
	'mail' => [

		'default' => (integer) 1,

	    'admin' => (string) '',

	    'fetching' => (boolean) false,

	    'acceptunknown' => (boolean) false,

	    'defaultmta' => (integer) 1,
	],

	//notifications
	'autorespond' => [
		'bymail' => (boolean) true,
	],
	'notify' => [
		'create' => [
			'admin' => (boolean) true,
			'mgr' => (boolean) true,
			'dept' => (boolean) true,
			'org' => (boolean) false,
			'owner' => (boolean) false,
			'assigned' => (boolean) true
		],
		'reply' => [
			'last' => (boolean) true,
			'assigned' => (boolean) true,
			'mgr' => (boolean) false,
			'org' => (boolean) false,
			'owner' => (boolean) false,
		],
		'comment' => [
			'assigned' => (boolean) true,
			'mgr' => (boolean) false,
		],
		'assign' => [
			'assigned' => (boolean) true,
			'mgr' => (boolean) false,
			'dept' => (boolean) false,
			'owner' => (boolean) false,
		],
		'transfer' => [
			'assigned' => (boolean) true,
			'mgr' => (boolean) true,
			'dept' => (boolean) false,
			'owner' => (boolean) false,
		],
		'edit' => [
			'assigned' => (boolean) true,
			'mgr' => (boolean) true,
			'owner' => (boolean) false,
		],
		'system' => [
			'admin' => (boolean) true,
		]
	],

];
