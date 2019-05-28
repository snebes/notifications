<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Contracts;

interface NotifiableInterface
{
    /**
     * Get the notification routing information for the given channel.
     *
     * @param string                     $channel
     * @param NotificationInterface|null $notification
     * @return mixed
     */
    public function routeNotificationFor(string $channel, NotificationInterface $notification = null);
}
