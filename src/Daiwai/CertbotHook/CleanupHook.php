<?php


namespace Daiwai\CertbotHook;

use SoapFault;

class CleanupHook extends AbstractHook implements Runnable
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

        $this->client->removeTxtRecord($reg_domain, $host);
        $this->logger->info('removed record');
    }
}
