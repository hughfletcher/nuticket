<?php

return array(
    'account_suffix' => env('ADLDAP_ACCOUNT_SUFFIX'),

    'domain_controllers' => explode('|', env('ADLDAP_DOMAIN_CONTROLLERS')), // An array of domains may be provided for load balancing.

    'base_dn' => env('ADLDAP_BASE_DN'),

    'admin_username' => env('ADLDAP_ADMIN_USER'),

    'admin_password' => env('ADLDAP_ADMIN_PASS'),
    'real_primary_group' => true, // Returns the primary group (an educated guess).

    'use_ssl' => false, // If TLS is true this MUST be false.

    'use_tls' => false, // If SSL is true this MUST be false.

    'recursive_groups' => true,

    'org_names' => explode('|', env('ADLDAP_ORG_NAMES'))


);