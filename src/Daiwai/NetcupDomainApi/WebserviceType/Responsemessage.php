<?php

/**
 * Response message of a request sent to the api.
 *
 * @pw_element string $serverrequestid Unique ID for the request, created by the server
 * @pw_element string $clientrequestid Unique ID for the request, created by the client
 * @pw_element string $action Name of the function that was called.
 * @pw_element string $status Staus of the Message like "error", "started", "pending", "warning" or "success".
 * @pw_element positiveInteger $statuscode Staus code of the Message like 2011.
 * @pw_element string $shortmessage Short message with information about the processing of the messsage.
 * @pw_element string $longmessage Long message with information about the processing of the messsage.
 * @pw_element string $responsedata Data from the response like domain object.
 * @pw_complex Responsemessage
 */
class Responsemessage
{
    /**
     * Unique ID for the request, created by the server
     *
     * @var string
     */
    public $serverrequestid;

    /**
     * Unique ID for the request, created by the client
     *
     * @var string
     */
    public $clientrequestid;

    /**
     * Name of the function that was called.
     *
     * @var string
     */
    public $action;

    /**
     * Staus of the Message like "error", "started", "pending", "warning" or "success".
     *
     * @var string
     */
    public $status;

    /**
     * Staus code of the Message like 2011.
     *
     * @var int
     */
    public $statuscode;

    /**
     * Short message with information about the processing of the messsage.
     *
     * @var string
     */
    public $shortmessage;

    /**
     * Long message with information about the processing of the messsage.
     *
     * @var string
     */
    public $longmessage;

    /**
     *
     * Data from the response like domain object.
     *
     */
    public $responsedata;
}
