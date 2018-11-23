<?php

return [
    // netcup customer ID
    'customer_id'   => '',

    // netcup API key
    'api_key'       => '',

    // netcup API password
    'api_pass'      => '',

    // the log level, value should be a valid PSR3 LogLevel
    'log_level'     => Psr\Log\LogLevel::NOTICE,

    // the interval in seconds for polling the API to check
    // whether the new record has been deployed
    'poll_interval' => 20, // default 20 s

    // maximum time in seconds to wait for the DNS records
    // to be deployed.
    'poll_limit'    => 900, // default 900 s (15 minutes)

    // the subdomain that should be used for the DNS challenge
    // this should be left as is
    'host'          => '_acme-challenge',
];
