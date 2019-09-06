<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications;

use SN\Notifications\Contracts\NotificationInterface;

/**
 * Adds routing methods for notifications.
 *
 * @property $email
 *
 * @author Steve Nebes <steve@nebes.net>
 */
trait NotifiableTrait
{
    use HasDatabaseNotifications;

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
            case 'database':
                return $this->getNotifications();
            case 'mail':
                return $this->email ?? null;
        }

        return null;
    }
}
