<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Contracts;

/**
 * Common method(s) for all Channels.
 */
interface ChannelInterface
{
    /**
     * Name of the channel.
     *
     * @return string
     */
    public static function getName(): string;

    /**
     * Send the given notification.
     *
     * @param NotifiableInterface   $notifiable
     * @param NotificationInterface $notification
     * @return mixed
     */
    public function send(NotifiableInterface $notifiable, NotificationInterface $notification);
}
