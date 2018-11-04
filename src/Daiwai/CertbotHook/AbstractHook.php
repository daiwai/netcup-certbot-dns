<?php


namespace Daiwai\CertbotHook;

use Daiwai\NetcupDomainApi\Client as ApiClient;
use LayerShifter\TLDExtract\Extract as TldExtractor;
use Psr\Log\LoggerInterface;

abstract class AbstractHook implements Runnable
{
    protected $logger;
    protected $client;
    protected $extractor;
    protected $config;

    /**
     * AuthHook constructor.
     *
     * @param ApiClient $client
     *
     * @param array $config
     *
     */
    public function __construct(ApiClient $client, TldExtractor $extractor, LoggerInterface $logger, $config)
    {
        $this->client = $client;
        $this->client->setLogger($logger);
        $this->extractor = $extractor;
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     *
     * @return null|string
     *
     */
    protected function readDomain()
    {
        return getenv('CERTBOT_DOMAIN');
    }

    protected function getRegisteredDomain($domain)
    {
        // replace * with a dummy value so the extractor gets a valid domain name to parse
        $domain = str_replace('*', 'wildcard', $domain);
        $parsed_domain = $this->extractor->parse($domain);

        return $parsed_domain->getRegistrableDomain();
    }

    /**
     *
     * @param string $cert_domain The domain name for the certificate e.g. '*.wildcard.example.com'
     *
     * @param string $reg_domain The registrable part of the domain, usually the 2nd or 3rd level domain e.g.
     * example.com
     *
     * @return string The 'host' part of the validation record e.g. '_acme-challenge.wildcard'
     *
     */
    protected function getHost($cert_domain, $reg_domain)
    {
        $host = $cert_domain;
        if(substr($host, 0, 2) === '*.')
        {
            $host = substr($host, 2);
        }

        $host = preg_replace('/' . preg_quote('.' . $reg_domain, '/') . '$/', '', $host);

        $host = $this->config['host'] . '.' . $host;

        return $host;
    }

    /**
     * @return array|false|string
     */
    protected function readValidationToken()
    {
        return getenv('CERTBOT_VALIDATION');
    }
}
