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
    public function getName(): string
    {
        return 'mail';
    }

    /**
     * Send the given notification.
     *
     * @param NotifiableInterface   $notifiable
     * @param NotificationInterface $notification
     *
     * @return mixed
     * @throws \RuntimeException
     */
    public function send(NotifiableInterface $notifiable, NotificationInterface $notification)
    {
        if (!\method_exists($notification, 'toMail')) {
            throw new \RuntimeException('Notification is missing toMail method.');
        }

        $toEmail = $notifiable->routeNotificationFor('mail', $notification);

        if (!$toEmail) {
            return null;
        }

        $email = $notification->toMail($notifiable);

        if ($email instanceof EmailInterface) {
            if (empty($email->getTo())) {
                $email->to($toEmail);
            }

            return $this->mailer->send($email);
        }

        return null;
    }
}
