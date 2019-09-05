<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Contracts;

interface ChannelInterface
{
    /**
     * Return channel short-name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Send the given notification.
     *
     * @param NotifiableInterface   $notifiable
     * @param NotificationInterface $notification
     *
     * @return mixed
     */
    public function send(NotifiableInterface $notifiable, NotificationInterface $notification);
}
