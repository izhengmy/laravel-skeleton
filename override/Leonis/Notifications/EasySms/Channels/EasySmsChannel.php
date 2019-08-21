<?php

namespace Override\Leonis\Notifications\EasySms\Channels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use Leonis\Notifications\EasySms\Channels\EasySmsChannel as Channel;
use Override\Leonis\Notifications\EasySms\Events\EasySmsNotificationFailed;
use Override\Leonis\Notifications\EasySms\Events\EasySmsNotificationSent;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class EasySmsChannel extends Channel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     * @return void
     * @throws \Overtrue\EasySms\Exceptions\NoGatewayAvailableException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function send($notifiable, Notification $notification)
    {
        if ($notifiable instanceof Model) {
            $to = $notifiable->routeNotificationForEasySms($notification);
        } elseif ($notifiable instanceof AnonymousNotifiable) {
            $to = $notifiable->routes[__CLASS__];
        } else {
            return;
        }

        /** @noinspection PhpUndefinedMethodInspection */
        $message = $notification->toEasySms($notifiable);

        try {
            $results = app()->make('easysms')->send($to, $message);

            event(new EasySmsNotificationSent($to, $message, $results));
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (NoGatewayAvailableException $e) {
            event(new EasySmsNotificationFailed($to, $message, $e));

            throw $e;
        }
    }
}
