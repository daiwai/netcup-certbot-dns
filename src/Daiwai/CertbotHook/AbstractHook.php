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
        $domain = getenv('CERTBOT_DOMAIN');
        // replace * with a dummy value so the extractor gets a valid domain name to parse
        $domain = str_replace('*', 'wildcard', $domain);
        $parsed_domain = $this->extractor->parse($domain);
        $domain = $parsed_domain->getRegistrableDomain();

        return $domain;
    }

    /**
     * @return array|false|string
     */
    protected function readValidationToken()
    {
        return getenv('CERTBOT_VALIDATION');
    }
}
