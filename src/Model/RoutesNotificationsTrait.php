<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Model;

use SN\Notifications\Channel\DatabaseChannel;
use SN\Notifications\Contracts\ChannelInterface;
use SN\Notifications\Contracts\NotificationInterface;

/**
 * Maps the notifiables' fields for the $channel.
 *
 * @method getNotifications()
 * @method getEmail()
 */
trait RoutesNotificationsTrait
{
    /**
     * @param string                $channel
     * @param NotificationInterface $notification
     * @return mixed
     */
    public function routeNotificationFor(string $channel, NotificationInterface $notification = null)
    {
        if (\class_exists($channel) && \in_array(ChannelInterface::class, \class_implements($channel))) {
            $suffix = \call_user_func([$channel, 'getName']);
        } else {
            $suffix = $channel;
        }

        $method = 'routeNotificationFor' . \ucfirst($suffix);

        if (\method_exists($this, $method)) {
            return $this->{$method}($notification);
        }

        switch ($channel) {
            case DatabaseChannel::class:
                return $this->getNotifications();
            case 'mail':
                return $this->getEmail();
        }

        return null;
    }
}
