<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Contracts;

use SN\Notifications\Event\NotificationSendEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
interface ChannelInterface extends EventSubscriberInterface
{
    /**
     * Return channel short-name.
     *
     * @return string
     */
    public static function getName(): string;

    /**
     * Send the given notification.
     *
     * @param NotificationSendEvent $event
     */
    public function send(NotificationSendEvent $event): void;
}
