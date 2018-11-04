<?php

/**
 * DNS record set
 *
 * @pw_element ArrayOfDnsrecord $dnsrecords Array of DNS records for a zone.
 * @pw_complex Dnsrecordset
 */
class Dnsrecordset
{
    /**
     * Array of DNS records for a zone.
     *
     * @var Dnsrecord[]
     */
    public $dnsrecords = [];

    public static function fromResponse($response)
    {
        $set = new static();
        foreach($response->responsedata->dnsrecords as $record)
        {
            $set->addRecord(Dnsrecord::fromObject($record));
        }

        return $set;
    }

    public function addRecord(Dnsrecord $record)
    {
        $this->dnsrecords[] = $record;
    }
}
