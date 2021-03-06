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
     * Gets the unique identifier of the notifiable.
     *
     * @return mixed
     */
    public function getId();

    /**
     * Gets the type identifier of the notifiable.
     *
     * @return string
     */
    public function getNotifiableType(): string;

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
