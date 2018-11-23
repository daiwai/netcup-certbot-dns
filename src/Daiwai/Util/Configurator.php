<?php

namespace Daiwai\Util;

use Psr\Log\LogLevel;
use InvalidArgumentException;

class Configurator
{
    private $base_path;

    private $in_vendor;

    private $options;

    private $default_config = [
        // netcup customer ID
        'customer_id'   => '',

        // netcup API key
        'api_key'       => '',

        // netcup API password
        'api_pass'      => '',

        // the log level, value should be a valid PSR3 LogLevel
        'log_level'     => LogLevel::NOTICE,

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

    public function __construct($cmd, $base_path, $options, $in_vendor)
    {
        $this->base_path = $base_path;
        $this->in_vendor = $in_vendor;
        $this->options = $options;

        $this->setupOptions($cmd, $in_vendor);
    }

    public function config()
    {
        $config = array_merge($this->default_config, $this->configFromFile());

        $config = $this->readCliOptions($config);

        if(!ctype_digit($config['customer_id']))
        {
            throw new InvalidArgumentException("Customer id should be a number");
        }

        if(trim($config['api_key']) === '')
        {
            throw new InvalidArgumentException("API key not configured");
        }

        if(trim($config['api_pass']) === '')
        {
            throw new InvalidArgumentException("API pass not configured");
        }

        return $config;
    }

    private function setupOptions($cmd, $in_vendor)
    {
        $cmd = $in_vendor
            ? 'vendor/bin/netcup-certbot-dns-' . $cmd
            : 'bin/' . $cmd;
        $this->options->addHead("Usage: $cmd [ options ]\n");
        $this->options->addRule('p|api-pass::', 'Netcup API pass');
        $this->options->addRule('k|api-key::', 'Netcup API key');
        $this->options->addRule('n|customer-id::', 'Netcup Customer ID number');
        $this->options->addRule('c|config::', 'Path to a config file');
        $this->options->addRule('q|quiet', 'Only output critical errors');

        $this->options->parse();

    }

    private function configFromFile()
    {
        $config_file = $this->options->getOption('c');

        if($config_file !== false)
        {
            if(!file_exists($config_file))
            {
                throw new InvalidArguementException("Config file '$config_file' not found\n");
            }

            return include $config_file;
        }

        if($this->in_vendor)
        {
            $config_file = $this->base_path . '/../../../../netcup-dns-config.php';
            if(file_exists($config_file))
            {
                return include $config_file;
            }
        }

        $config_file = $this->base_path . '/../config.php';

        if(file_exists($config_file))
        {
            return include $config_file;
        }

        return [];
    }

    private function readCliOptions($config)
    {
        $customer_id = $this->options->getOption('n');
        if($customer_id !== false)
        {
            $config['customer_id'] = $customer_id;
        }

        $api_key = $this->options->getOption('k');
        if($api_key !== false)
        {
            $config['api_key'] = $api_key;
        }

        $api_pass = $this->options->getOption('p');
        if($api_pass !== false)
        {
            $config['api_pass'] = $api_pass;
        }

        $quiet = $this->options->getOption('q');
        if($quiet !== false)
        {
            $config['log_level'] = LogLevel::EMERGENCY;
        }

        return $config;
    }

    public function getUsage()
    {
        return $this->options->getUsage();
    }

    public function getOption($name)
    {
        return $this->options->getOption($name);
    }
}
