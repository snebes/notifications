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
use SN\Notifications\Event\NotificationSendEvent;
use SN\Notifications\NotificationEvents;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class DatabaseChannel implements ChannelInterface
{
    /**
     * @var string
     */
    private $notificationEntityClass;

    /**
     * Default values.
     *
     * @param string $notificationEntityClass
     */
    public function __construct(string $notificationEntityClass = Notification::class)
    {
        $this->notificationEntityClass = $notificationEntityClass;
    }

    /**
     * Return channel short-name.
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'database';
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            NotificationEvents::SEND => [['send', 255]],
        ];
    }

    /**
     * Send the given notification.
     *
     * @param NotificationSendEvent $event
     */
    public function send(NotificationSendEvent $event): void
    {
        if ($event->getChannel() !== static::getName()) {
            return;
        }

        $notifiable = $event->getNotifiable();
        $notification = $event->getNotification();
        $entityClass = $this->notificationEntityClass;

        /** @var Notification $entity */
        $entity = new $entityClass();
        $entity->setNotifiableType($notifiable->getNotifiableType());
        $entity->setNotifiableId($notifiable->getNotifiableId());
        $entity->setData($this->getData($notifiable, $notification));

        $notifiable->routeNotificationFor('database', $notification)->add($entity);

        $event->setResponse($entity);
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
