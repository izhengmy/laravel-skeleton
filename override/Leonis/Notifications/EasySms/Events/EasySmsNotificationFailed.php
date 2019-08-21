<?php

namespace Override\Leonis\Notifications\EasySms\Events;

use Exception;
use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;

class EasySmsNotificationFailed
{
    /**
     * @var \Overtrue\EasySms\Contracts\PhoneNumberInterface
     */
    public $to;

    /**
     * @var \Overtrue\EasySms\Contracts\MessageInterface
     */
    public $message;

    /**
     * @var \Overtrue\EasySms\Exceptions\NoGatewayAvailableException
     */
    public $exception;

    /**
     * Create a new EasySmsNotificationFailed instance.
     *
     * @param  \Overtrue\EasySms\Contracts\PhoneNumberInterface  $to
     * @param  \Overtrue\EasySms\Contracts\MessageInterface  $message
     * @param  \Exception  $exception
     * @return void
     */
    public function __construct(PhoneNumberInterface $to, MessageInterface $message, Exception $exception)
    {
        $this->to = $to;
        $this->message = $message;
        $this->exception = $exception;
    }
}
