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
	]

];
