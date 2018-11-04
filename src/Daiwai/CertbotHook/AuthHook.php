<?php

namespace Daiwai\CertbotHook;

class AuthHook extends AbstractHook implements Runnable
{
    public function run()
    {
        $domain = $this->readDomain();
        $validation_token = $this->readValidationToken();

        $this->client->setTxtRecord($domain, $this->config['host'], $validation_token);

        $this->poll($domain, $validation_token);

        $this->logger->debug('done');
    }

    /**
     * @param $domain
     * @param $validation_token
     * @throws \SoapFault
     */
    private function poll($domain, $validation_token)
    {
        $t0 = time();
        while(true)
        {
            $t1 = time();
            $poll_time = $t1 - $t0;
            if($poll_time > $this->config['poll_limit'])
            {
                break;
            }

            $this->logger->info("sleeping {$this->config['poll_interval']} s (polled for $poll_time s)");

            sleep($this->config['poll_interval']);

            $this->logger->info( "polling ...");
            $deployed = $this->client->pollTxtRecord($domain, $this->config['host'], $validation_token);
            $this->logger->info("deployed: " . ($deployed ? 'yes' : 'no'));

            if($deployed)
            {
                break;
            }
        }
    }
}
