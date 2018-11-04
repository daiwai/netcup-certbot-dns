<?php


namespace Daiwai\CertbotHook;


class CleanupHook extends AbstractHook implements Runnable
{
    public function run()
    {
        $domain = $this->readDomain();
        $this->client->removeTxtRecord($domain, $this->config['host']);
        $this->logger->info('removed record');
    }
}
