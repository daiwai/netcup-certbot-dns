<?php

namespace Daiwai\CertbotHook;

use SoapFault;

class AuthHook extends AbstractHook implements Runnable
{
    /**
     *
     * @throws SoapFault
     *
     */
    public function run()
    {
        $cert_domain = $this->readDomain();
        $reg_domain = $this->getRegisteredDomain($cert_domain);
        $host = $this->getHost($cert_domain, $reg_domain);

        $validation_token = $this->readValidationToken();

        $this->client->setTxtRecord($reg_domain, $host, $validation_token);

        $this->poll($reg_domain, $host, $validation_token);

        $this->logger->debug('done');
    }


    /**
     *
     * @param $reg_domain
     * @param $validation_token
     * @throws SoapFault
     *
     */
    private function poll($reg_domain, $host, $validation_token)
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
            $deployed = $this->client->pollTxtRecord($reg_domain, $host, $validation_token);
            $this->logger->info("deployed: " . ($deployed ? 'yes' : 'no'));

            if($deployed)
            {
                break;
            }
        }
    }
}
