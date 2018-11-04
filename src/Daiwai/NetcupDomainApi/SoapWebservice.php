<?php

namespace Daiwai\NetcupDomainApi;

use SessionObject;
use Dnsrecordset;
use Dnszone;
use Exception;
use SoapClient;
use SoapFault;
use Responsemessage;

/**
 * Example client to access the domain reselling API.
 * Please note: We cannot guarantee the function of this client.
 * @service SoapWebservice
 */
class SoapWebservice
{
    /**
     * The WSDL URI
     *
     * @var string
     */
    private $wsdl_uri = 'https://ccp.netcup.net/run/webservice/servers/endpoint.php?WSDL';

    /**
     * The PHP SoapClient object
     *
     * @var SoapClient
     */
    private $server;

    public function __construct()
    {
        $options = [
            'exceptions' => true,
        ];

        $this->server = new SoapClient($this->wsdl_uri, $options);
    }

    /**
     * Send a SOAP request to the server
     *
     * @param string $method The method name
     * @param array $param The parameters
     *
     * @return mixed The server response
     *
     * @throws SoapFault
     *
     */
    private function call($method, $param)
    {
        try {
            return $this->server->__soapCall($method, $param);
        } catch (Exception $exception) {
            throw new SoapFault($exception->faultstring, $exception->faultcode, $exception);
        }
    }

    /**
     *
     * End session for API user.
     * A login has to be send before each request.
     * This function is available for domain resellers.
     *
     * @param int|string $customer_id customer number of reseller at netcup.
     * @param string $api_key Unique API key generated in customer control panel.
     * @param string $api_session_id Unique API session id created by login command.
     * @param string $request_id Id from client side. Can contain letters and numbers. Field is optional.
     *
     * @return Responsemessage $responsemessage with information about result of the action like short and long
     * resultmessages, message status, etc.
     *
     * @throws SoapFault
     *
     */
    public function logout($customer_id, $api_key, $api_session_id, $request_id)
    {
        return $this->call('logout', func_get_args());
    }

    /**
     *
     * Create a login session for API users.
     * A login has to be send before each request.
     * This function is avaliable for domain resellers.
     *
     * @param int|string $customer_id customer number of reseller at netcup.
     * @param string $api_key Unique API key generated in customer control panel.
     * @param string $pass API password set in customer control panel.
     * @param string $request_id Id from client side. Can contain letters and numbers. Field is optional.
     *
     * @return SessionObject string $apisessionid Server generated ID to be used with further
     * requests when login was successful.
     *
     * @throws SoapFault
     *
     */
    public function login($customer_id, $api_key, $pass, $request_id)
    {
        return SessionObject::fromResponse($this->call('login', func_get_args()));
    }

    /**
     *
     * Get all records of a zone.
     * Zone must be owned by customer.
     *
     * @param string $domain_name Name of the domain including top-level domain.
     * @param int|string $customer_id customer number of reseller at netcup.
     * @param string $api_key Unique API key generated in customer control panel.
     * @param string $api_session_id Unique API session id created by login command.
     * @param string $request_id Id from client side. Can contain letters and numbers. Field is optional.
     *
     * @return Dnsrecordset
     *
     * @throws SoapFault
     *
     */
    public function infoDnsRecords($domain_name, $customer_id, $api_key, $api_session_id, $request_id)
    {
        return Dnsrecordset::fromResponse($this->call('infoDnsRecords', func_get_args()));
    }

    /**
     *
     * Update DNS records of a zone. Deletion of other records is optional.
     * When DNSSEC is active, the zone is updated in the name server with zone resign after a few minutes.
     *
     * @param string $domain_name Name of the domain including top-level domain.
     * @param int|string $customer_id customer number of reseller at netcup.
     * @param string $api_key Unique API key generated in customer control panel.
     * @param string $api_session_id Unique API session id created by login command.
     * @param string $request_id Id from client side. Can contain letters and numbers. Field is optional.
     * @param Dnsrecordset $dnsrecordset Object that contains DNS Records.
     *
     * @return Responsemessage $responsemessage with information about result of the action like short and long
     * resultmessages, message status, etc.
     *
     * @throws SoapFault
     *
     */
    public function updateDnsRecords($domain_name, $customer_id, $api_key, $api_session_id, $request_id, $dnsrecordset)
    {
        return $this->call('updateDnsRecords', func_get_args());
    }

    /**
     *
     * Update DNS zone.
     * When DNSSEC is active, the zone is updated in the name server with zone resign after a few minutes.
     *
     * @param string $domain_name Name of the domain including top-level domain.
     * @param int|string $customer_id customer number of reseller at netcup.
     * @param string $api_key Unique API key generated in customer control panel.
     * @param string $api_session_id Unique API session id created by login command.
     * @param string $request_id Id from client side. Can contain letters and numbers. Field is optional.
     * @param Dnszone $dns_zone Object that contains settings for DNS zone.
     *
     * @return Responsemessage $responsemessage with information about result of the action like short and long
     * resultmessages, message status, etc.
     *
     * @throws SoapFault
     *
     */
    public function updateDnsZone($domain_name, $customer_id, $api_key, $api_session_id, $request_id, $dns_zone)
    {
        return $this->call('updateDnsZone', func_get_args());
    }

    /**
     *
     * Get information about dns zone in local name servers.
     * Zone must be owned by reseller.
     *
     * @param string $domain_name Name of the domain including top-level domain.
     * @param int|string $customer_id customer number of reseller at netcup.
     * @param string $api_key Unique API key generated in customer control panel.
     * @param string $api_session_id Unique API session id created by login command.
     * @param string $request_id Id from client side. Can contain letters and numbers. Field is optional.
     *
     * @return Responsemessage $responsemessage with information about result of the action like short and long
     * resultmessages, message status, etc.
     *
     * @throws SoapFault
     *
     */
    public function infoDnsZone($domain_name, $customer_id, $api_key, $api_session_id, $request_id)
    {
        return $this->call('infoDnsZone', func_get_args());
    }
}
