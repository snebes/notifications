<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Model;

/**
 * Maps the notifiables' fields for the $channel.
 *
 * @property string $email
 * @property string $phoneNumber
 */
trait RoutesNotificationsTrait
{
    /**
     * @param string $channel
     * @param mixed  $notification
     * @return mixed
     */
    public function routeNotificationFor(string $channel, $notification = null)
    {
        $method = 'routeNotificationFor' . \ucfirst($channel);

        if (\method_exists($this, $method)) {
            return $this->{$method}($notification);
        }

        switch ($channel) {
            case 'database':
                return $this->getNotifications();
            case 'mail':
                return $this->email;
            case 'sms':
                return $this->phoneNumber;
        }

        return null;
    }
}
