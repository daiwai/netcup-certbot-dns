<?php

/**
 *
 * DNS zone
 *
 */
class Dnszone
{
    /**
     *
     * @var string Name of the zone - this is a domain name.
     *
     */
    public $name;

    /**
     *
     * @var int  time-to-live Time in seconds a domain name is cached locally before expiration and return to
     * authoritative nameservers for updated information. Recommendation: 3600 to 172800
     *
     */
    public $ttl;

    /**
     *
     * @var int serial of the zone. Readonly.
     *
     */
    public $serial;

    /**
     *
     * @var int Time in seconds a secondary name server waits to check for a new copy of a DNS zone.
     * Recommendation: 3600 to 14400
     *
     */
    public $refresh;

    /**
     *
     * @var int Time in seconds primary name server waits if an attempt to refresh by a secondary name server failed.
     * Recommendation: 900 to 3600
     *
     */
    public $retry;

    /**
     *
     * @var int Time in seconds a secondary name server will hold a zone before it is no longer considered authoritative.
     * Recommendation: 592200 to 1776600
     *
     */
    public $expire;

    /**
     *
     * @var boolean Status of DNSSSEC in this nameserver. Enabling DNSSEC is possible once every 24 hours.
     *
     */
    public $dnssecstatus;
}
