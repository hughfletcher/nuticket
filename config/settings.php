<?php

return [

    'title' => 'NuTicket :: Ticket Support System',

	'pagesize' => 25,

	'time' => [
		'enabled' => true,
		'edit' => true
	],

	'default' => [
		'tz' => 'US/Central',
		'dept' => 1,
		'pagesize' => 25,
		'priority' => 3,
		'org' => 1
 	],

	'format' => [
		'date' => 'm/d/Y',
		'dateday' => 'm/d/Y D',
        'datetime' => 'm/d/Y g:i a'
	],

	'theme' => 'default',

	//access
	'registration' => [
		'required' => true,
		'method' => false //public,private,false
	]

];
