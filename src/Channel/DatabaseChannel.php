<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Channel;

use Doctrine\Common\Collections\Collection;
use SN\Notifications\Contracts\ChannelInterface;
use SN\Notifications\Contracts\NotifiableInterface;

class DatabaseChannel implements ChannelInterface
{
    private $em;

    public function __construct(RegistryInterface $registry)
    {

    }

    /**
     * @param NotifiableInterface $notifiable
     * @param mixed               $notification
     */
    public function send(NotifiableInterface $notifiable, $notification): void
    {
        $dbNotification = $notification->getDatabaseNotification();

        if (null === $dbNotification) {
            return;
        }

        /** @var Collection $notificationCollection */
        $notificationCollection = $notifiable->routeNotificationFor(static::class, $notification);

    }
}
