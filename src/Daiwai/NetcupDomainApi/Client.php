<?php

namespace Daiwai\NetcupDomainApi;

use Dnsrecord;
use Dnsrecordset;
use SoapFault;
use Psr\Log\LoggerAwareTrait;

class Client
{
    protected $client;
    protected $id;
    protected $key;
    protected $pw;
    protected $req_id;
    protected $sess;

    use LoggerAwareTrait;

    /**
     * Client constructor.
     *
     * @param SoapWebservice $client
     * @param string $id The netcup customer ID
     * @param string $key The netcup API key
     * @param string $pw The netcup API password
     *
     */
    public function __construct(SoapWebservice $client, $id, $key, $pw)
    {
        $this->client = $client;
        $this->id = $id;
        $this->key = $key;
        $this->pw = $pw;
    }

    /**
     *
     * Sets the value for the TXT DNS record identified by the domain name and a host name. If the record already
     * exists, it is updated with the new value. Otherwise it is created.
     *
     * For example, to set the TXT DNS record for foo.example.com. to 'bar' $domain should be 'example.com', and $host
     * should be 'foo', $value should be 'bar'.
     *
     * @param string $domain The domain name for which to set the TXT record
     * @param string $host The host name for which to set the TXT record
     * @param string $value The TXT record's new value
     *
     * @throws SoapFault
     *
     */
    public function setTxtRecord($domain, $host, $value)
    {
        $this->login();

        $recordset = $this->fetchRecords($domain);

        $record = $this->findRecord($host, 'TXT', $recordset);

        if(!$record)
        {
            $record = new Dnsrecord();
            $record->hostname       = $host;
            $record->type           = 'TXT';
            $record->priority       = 0;
            $record->deleterecord   = false;
            $recordset->dnsrecords[] = $record;
        }

        $record->destination = $value;

        $this->client->updateDnsRecords($domain, $this->id, $this->key, $this->sess, $this->req_id, $recordset);

        $this->logout();
    }

    /**
     *
     * Removes the TXT record identified by domain name and host name
     *
     * @param string $domain The domain name
     * @param string $host The host name
     *
     * @throws SoapFault
     *
     */
    public function removeTxtRecord($domain, $host)
    {
        $this->login();

        $record_set = $this->fetchRecords($domain);
        $record = $this->findRecord($host, 'TXT', $record_set);

        // record doesn't exist, we don't need to delete it
        if(!$record)
        {
            return;
        }

        $record->deleterecord = true;

        $this->client->updateDnsRecords($domain, $this->id, $this->key, $this->sess, $this->req_id, $record_set);

        $this->logout();
    }

    /**
     *
     * Polls whether a TXT record identified by a domain and host exists and its value matches
     *
     * @param string $domain The domain name
     * @param string $host The host name
     * @param string $value The expected value
     *
     * @return bool True if the record exists and its value is equal to $value
     *
     * @throws SoapFault
     *
     */
    public function pollTxtRecord($domain, $host, $value)
    {
        $this->login();

        $this->logger->debug("polling $host, $domain for '$value'");

        $record_set = $this->fetchRecords($domain);
        $record = $this->findRecord($host, 'TXT', $record_set);
        if($record === null)
        {
            $this->logger->debug('poll failed no record');
            $this->logout();

            return false;
        }

        if($record->destination !== $value)
        {
            $this->logger->debug("poll failed value {$record->destination} !== {$value}");
            $this->logout();

            return false;
        }

        if(!$record->isValid())
        {
            $this->logger->debug("poll failed state {$record->state} is not valid");
            $this->logout();

            return false;
        }
        $this->logout();
        $this->logger->debug('poll success');

        return true;
    }

    /**
     *
     * Find a record from a set matching a host and type
     *
     * @param $host
     * @param $type
     * @param $recordset
     *
     * @return Dnsrecord|null The record if it exists or null
     *
     */
    private function findRecord($host, $type, $recordset)
    {
        foreach($recordset->dnsrecords as $record)
        {
            if($record->hostname === $host && $record->type === $type)
            {
                return $record;
            }
        }

        return null;
    }

    /**
     *
     * @param $domain
     * @return Dnsrecordset
     *
     * @throws SoapFault
     *
     */
    private function fetchRecords($domain)
    {
        return $this->client->infoDnsRecords($domain, $this->id, $this->key, $this->sess, $this->req_id);
    }

    /**
     * @throws SoapFault
     */
    private function login()
    {
        $this->req_id = uniqid('', false);
        $this->logger->debug("Set request id to {$this->req_id}");
        $session = $this->client->login($this->id, $this->key, $this->pw, $this->req_id);
        $this->sess = $session->getId();
        $this->logger->debug("Logged in, received session ID {$this->sess}");
    }

    /**
     * @throws SoapFault
     */
    private function logout()
    {
        $this->client->logout($this->id, $this->key, $this->sess, $this->req_id);

        $this->sess = null;
        $this->req_id = null;
    }
}
