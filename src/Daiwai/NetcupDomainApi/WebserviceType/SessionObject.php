<?php

/**
 *
 * Object that is returned after successful login.
 *
 */
class SessionObject
{
    /**
     *
     * @var string Unique API session id created by login command
     *
     */
    public $apisessionid;

    public function __construct($session_id)
    {
        $this->apisessionid = $session_id;
    }

    public static function fromResponse($response)
    {
        return new static($response->responsedata->apisessionid);
    }

    public function getId()
    {
        return $this->apisessionid;
    }
}
