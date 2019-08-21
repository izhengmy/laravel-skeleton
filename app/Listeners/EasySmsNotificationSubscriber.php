<?php

namespace App\Listeners;

use App\Models\EasySmsLog;
use Override\Leonis\Notifications\EasySms\Events\EasySmsNotificationFailed;
use Override\Leonis\Notifications\EasySms\Events\EasySmsNotificationSent;
use Overtrue\EasySms\Contracts\MessageInterface;

class EasySmsNotificationSubscriber
{
    /**
     * @param  \Override\Leonis\Notifications\EasySms\Events\EasySmsNotificationSent  $event
     * @return void
     */
    public function handleSent(EasySmsNotificationSent $event)
    {
        (new EasySmsLog([
            'mobile_number' => $event->to->getNumber(),
            'message' => $this->formatMessage($event->message),
            'results' => $this->formatResults($event->results),
            'successful' => true,
        ]))->save();
    }

    /**
     * @param  \Override\Leonis\Notifications\EasySms\Events\EasySmsNotificationFailed  $event
     * @return void
     */
    public function handleFailed(EasySmsNotificationFailed $event)
    {
        (new EasySmsLog([
            'mobile_number' => $event->to->getNumber(),
            'message' => $this->formatMessage($event->message),
            'results' => $this->formatResults($event->exception->getResults()),
            'successful' => false,
        ]))->save();
    }

    /**
     * Format the easy sms message.
     *
     * @param  \Overtrue\EasySms\Contracts\MessageInterface  $message
     * @return array
     */
    protected function formatMessage(MessageInterface $message)
    {
        return [
            'messageType' => $message->getMessageType(),
            'content' => $message->getContent(),
            'template' => $message->getTemplate(),
            'data' => $message->getData(),
            'gateways' => $message->getGateways(),
        ];
    }

    /**
     * Format the easy sms results.
     *
     * @param  array  $results
     * @return array
     */
    protected function formatResults(array $results)
    {
        foreach ($results as $key => $result) {
            if ('failure' == $result['status']) {
                $result['exception'] = (object) [
                    'message' => $result['exception']->getMessage(),
                    'code' => $result['exception']->getCode(),
                ];
                $results[$key] = $result;
            }
        }

        return $results;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(EasySmsNotificationSent::class, self::class.'@handleSent');
        $events->listen(EasySmsNotificationFailed::class, self::class.'@handleFailed');
    }
}
