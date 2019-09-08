<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Contracts;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
interface NotifiableInterface
{
    /**
     * Return a type identifier of the notifiable, typically the class name.
     *
     * @return string
     */
    public function getNotifiableType(): string;

    /**
     * Return a unique identifier of the notifiable, typically the database primary key.
     *
     * @return string
     */
    public function getNotifiableId(): string;

    /**
     * Get the notification routing information for the given channel.
     *
     * @param string                     $channel
     * @param NotificationInterface|null $notification
     *
     * @return mixed|null
     */
    public function routeNotificationFor(string $channel, NotificationInterface $notification = null);
}
