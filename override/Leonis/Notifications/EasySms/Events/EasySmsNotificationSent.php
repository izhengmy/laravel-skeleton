<?php

namespace Override\Leonis\Notifications\EasySms\Events;

use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;

class EasySmsNotificationSent
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
     * @var array
     */
    public $results;

    /**
     * Create a new EasySmsNotificationSent instance.
     *
     * @param  \Overtrue\EasySms\Contracts\PhoneNumberInterface  $to
     * @param  \Overtrue\EasySms\Contracts\MessageInterface  $message
     * @param  array  $results
     * @return void
     */
    public function __construct(PhoneNumberInterface $to, MessageInterface $message, array $results)
    {
        $this->to = $to;
        $this->message = $message;
        $this->results = $results;
    }
}
