<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Leonis\Notifications\EasySms\Messages\EasySmsMessage;
use Override\Leonis\Notifications\EasySms\Channels\EasySmsChannel;

class AuthSmsCaptcha extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The code of sms captcha.
     *
     * @var string
     */
    protected $code;

    /**
     * Create a new notification instance.
     *
     * @param  string  $code
     * @return void
     */
    public function __construct(string $code)
    {
        $this->onQueue('sms');
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(/** @noinspection PhpUnusedParameterInspection */ $notifiable)
    {
        return [EasySmsChannel::class];
    }

    /**
     * Get the easy sms representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Leonis\Notifications\EasySms\Messages\EasySmsMessage
     */
    public function toEasySms(/** @noinspection PhpUnusedParameterInspection */ $notifiable)
    {
        return (new EasySmsMessage())
            ->setContent("您的验证码是 {$this->code}，此验证码用于登录或重置密码。10 分钟内有效。")
            ->setTemplate('SMS_001')
            ->setData(['code' => $this->code]);
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return ['admin.auth-sms-captcha'];
    }
}
