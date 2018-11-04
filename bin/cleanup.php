#!/usr/bin/php
<?php

use Daiwai\CertbotHook\CleanupHook;
use Daiwai\NetcupDomainApi\SoapWebservice;
use Daiwai\NetcupDomainApi\Client;
use LayerShifter\TLDExtract\Extract as TldExtractor;
use fool\echolog\Echolog;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php';

$log_level = $argc == 2 && $argv[1] === '-q'
    ? Psr\Log\LogLevel::EMERGENCY
    : $config['log_level'];
try
{
    $hook = new CleanupHook(
        new Client(new SoapWebservice(), $config['customer_id'], $config['api_key'], $config['api_pass']),
        new TldExtractor(), new Echolog($log_level),
        $config
    );
    $hook->run();
}
catch(Exception $e)
{
    exit(1);
}
