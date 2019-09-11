<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications;

use SN\Notifications\Channel\DatabaseChannel;
use SN\Notifications\Channel\MailChannel;
use SN\Notifications\Contracts\NotificationInterface;

/**
 * Adds routing methods for notifications.
 *
 * @property $email
 * @property $id
 * @property $phoneNumber
 *
 * @author Steve Nebes <steve@nebes.net>
 */
trait NotifiableTrait
{
    use HasDatabaseNotificationsTrait;

    /**
     * Gets the unique identifier of the notifiable.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the type identifier of the notifiable.
     *
     * @return string
     */
    public function getNotifiableType(): string
    {
        return \get_class($this);
    }

    /**
     * Get the notification routing information for the given channel.
     *
     * @param string                     $channel
     * @param NotificationInterface|null $notification
     *
     * @return mixed|null
     */
    public function routeNotificationFor(string $channel, NotificationInterface $notification = null)
    {
        if (\method_exists($this, $method = 'routeNotificationFor' . $channel)) {
            return $this->{$method}($notification);
        }

        switch ($channel) {
            case DatabaseChannel::getName():
                return $this->getNotifications();
            case MailChannel::getName():
                return $this->email ?? null;
        }

        return null;
    }
}
