<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Channel;

use SN\Notifications\Contracts\ChannelInterface;
use SN\Notifications\Contracts\EmailInterface;
use SN\Notifications\Contracts\MailerInterface;
use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Contracts\NotificationInterface;
use SN\Notifications\Event\NotificationSendEvent;
use SN\Notifications\NotificationEvents;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class MailChannel implements ChannelInterface
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * Default values.
     *
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Return channel short-name.
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'mail';
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            NotificationEvents::SEND => [['send', 255]],
        ];
    }

    /**
     * Send the given notification.
     *
     * @param NotificationSendEvent $event
     */
    public function send(NotificationSendEvent $event): void
    {
        if ($event->getChannel() !== static::getName()) {
            return;
        }

        $notifiable = $event->getNotifiable();
        $notification = $event->getNotification();

        $email = $this->getData($notifiable, $notification);

        if ($email instanceof EmailInterface) {
            $response = $this->mailer->send($email);
            $event->setResponse($response);
        }
    }

    /**
     * Get the data for the notification.
     *
     * @param NotifiableInterface   $notifiable
     * @param NotificationInterface $notification
     *
     * @return EmailInterface
     * @throws \RuntimeException
     */
    protected function getData(NotifiableInterface $notifiable, NotificationInterface $notification): EmailInterface
    {
        if (\method_exists($notification, 'toMail')) {
            $email = $notification->toMail($notifiable);

            if (!$email instanceof EmailInterface) {
                throw new \RuntimeException('toMail should return an EmailInterface object.');
            }

            $toEmail = $notifiable->routeNotificationFor('mail', $notification);

            if (empty($toEmail) && empty($email->getTo())) {
                throw new \RuntimeException('DatabaseNotification does contain a To email address.');
            }

            if (empty($email->getTo())) {
                $email->to($toEmail);
            }

            return $email;
        }

        throw new \RuntimeException('DatabaseNotification is missing toMail method.');
    }
}
