<?php

namespace KSearchClient\Model\Data;

use JMS\Serializer\Annotation as JMS;

class DataStatus
{
    const STATUS_OK = 'ok';
    const STATUS_QUEUED = 'queued';
    const STATUS_ERROR = 'error';

    /**
     * The status.
     *
     * @var string
     * @JMS\Type("string")
     */
    public $status;

    /**
     * The status description message, if any.
     *
     * @var string
     * @JMS\Type("string")
     */
    public $message;
}
