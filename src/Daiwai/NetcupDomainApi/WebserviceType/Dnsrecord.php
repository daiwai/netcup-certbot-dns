<?php

/**
 *
 * DNS record.
 *
 */
class Dnsrecord
{
    const STATUS_VALID = 'yes';

    /**
     *
     * @var int Unique ID of the record. Leave id empty for new records.
     *
     */
    public $id;

    /**
     *
     * @var string Hostname of the record. Use '@' for root of domain.
     *
     */
    public $hostname;

    /**
     *
     * @var string Type of Record like A or MX.
     *
     */
    public $type;

    /**
     *
     * @var string Required for MX records.
     *
     */
    public $priority;

    /**
     *
     * @var string Target or value of the record.
     *
     */
    public $destination;

    /**
     *
     * @var bool TRUE when record will be deleted|boolean FALSE when record will persist
     *
     */
    public $deleterecord;

    /**
     *
     * @var string State of the record. Read only, inputs are ignored. Possible values are 'yes' if the record was
     * deployed and on the name server and is valid, 'unknown' otherwise
     *
     */
    public $state;


    public function __construct($id = null, $hostname = null, $type = null, $priority = null, $destination = null, $deleterecord = null, $state = null)
    {
        $this->id = $id;
        $this->hostname = $hostname;
        $this->type = $type;
        $this->priority = $priority;
        $this->destination = $destination;
        $this->deleterecord = $deleterecord;
        $this->state = $state;
    }

    public static function fromObject($obj)
    {
        return new static(
            $obj->id,
            $obj->hostname,
            $obj->type,
            $obj->priority,
            $obj->destination,
            $obj->deleterecord,
            $obj->state
        );
    }

    public function isValid()
    {
        return $this->state === self::STATUS_VALID;
    }
}
