<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Channel;

use SN\Notifications\Contracts\ChannelInterface;
use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Contracts\NotificationInterface;
use SN\Notifications\Entity\Notification;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class DatabaseChannel implements ChannelInterface
{
    /**
     * Return channel short-name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'database';
    }

    /**
     * Send the given notification.
     *
     * @param NotifiableInterface   $notifiable
     * @param NotificationInterface $notification
     *
     * @return mixed
     */
    public function send(NotifiableInterface $notifiable, NotificationInterface $notification)
    {
        $entity = new Notification();
        $entity->setData($this->getData($notifiable, $notification));

        $notifiable->routeNotificationFor('database', $notification)->add($entity);

        return $entity;
    }

    /**
     * Get the data for the notification.
     *
     * @param NotifiableInterface   $notifiable
     * @param NotificationInterface $notification
     *
     * @return array
     * @throws \RuntimeException
     */
    protected function getData(NotifiableInterface $notifiable, NotificationInterface $notification): array
    {
        if (\method_exists($notification, 'toDatabase')) {
            $data = $notification->toDatabase($notifiable);

            return (array) $data;
        }

        throw new \RuntimeException('Notification is missing toDatabase method.');
    }
}
