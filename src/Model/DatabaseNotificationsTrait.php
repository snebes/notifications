<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;

/**
 * @property $notifications
 */
trait DatabaseNotificationsTrait
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
     * Get the entitys' read notifications.
     *
     * @return array
     */
    public function getReadNotifications(): array
    {
        /** @var Selectable $notifications */
        $notifications = $this->getNotifications();

        $criteria = Criteria::create();
        $criteria->where($criteria->expr()->neq('readAt', null));

        return $notifications->matching($criteria)->toArray();
    }

    /**
     * Get the entitys' unread notifications.
     *
     * @return array
     */
    public function getUnreadNotifications(): array
    {
        /** @var Selectable $notifications */
        $notifications = $this->getNotifications();

        $criteria = Criteria::create();
        $criteria->where($criteria->expr()->eq('readAt', null));

        return $notifications->matching($criteria)->toArray();
    }
}