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
     * @param NotifiableInterface $notifiable
     * @param mixed               $notification
     */
    public function send(NotifiableInterface $notifiable, $notification): void;
}
