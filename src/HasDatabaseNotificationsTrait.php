<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Selectable;
use SN\Notifications\Contracts\NotificationInterface;
use SN\Notifications\Model\DatabaseNotification;

/**
 * Database notification methods.
 *
 * @property DatabaseNotification[] $notifications
 *
 * @author Steve Nebes <steve@nebes.net>
 */
trait HasDatabaseNotificationsTrait
{
    /**
     * Get the entitys' notifications.
     *
     * @return Collection
     */
    public function getNotifications(): Collection
    {
        $this->notifications = $this->notifications ?? new ArrayCollection();

        return $this->notifications;
    }

    /**
     * Get the entity's read notifications.
     *
     * @return NotificationInterface[]
     */
    public function getReadNotifications(): array
    {
        /** @var Selectable $notifications */
        $notifications = $this->getNotifications();

        return $notifications->filter(function (DatabaseNotification $notification) {
            return null !== $notification->getReadAt();
        })->toArray();
    }

    /**
     * Get the entity's read notifications.
     *
     * @return NotificationInterface[]
     */
    public function getUnreadNotifications(): array
    {
        /** @var Selectable $notifications */
        $notifications = $this->getNotifications();

        return $notifications->filter(function (DatabaseNotification $notification) {
            return null === $notification->getReadAt();
        })->toArray();
    }
}
