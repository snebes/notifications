<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Channel;

use Doctrine\ORM\EntityManager;
use RuntimeException;
use SN\Notifications\Contracts\ArrayableInterface;
use SN\Notifications\Contracts\ChannelInterface;
use SN\Notifications\Contracts\DatabaseNotificationInterface;
use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Contracts\NotificationInterface;
use SN\Notifications\Model\DatabaseNotification;
use SN\Notifications\Event\NotificationSendEvent;
use SN\Notifications\NotificationEvents;

/**
 * @author Steve Nebes <steve@nebes.net>
 * @internal
 */
class DatabaseChannel implements ChannelInterface
{
    /**
     * @var EntityManager|null
     */
    private $em;

    /**
     * @var string
     */
    private $notificationClass;

    /**
     * Default values.
     *
     * @param EntityManager|null $em
     * @param string        $notificationClass
     */
    public function __construct(?EntityManager $em, string $notificationClass = DatabaseNotification::class)
    {
        $this->em = $em;
        $this->notificationClass = $notificationClass;
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
     *
     * @throws \Exception
     */
    public function send(NotificationSendEvent $event): void
    {
        if (null === $this->em) {
            return;
        }

        if ($event->getChannel() !== static::getName()) {
            return;
        }

        if (!\in_array(DatabaseNotificationInterface::class, \class_implements($this->notificationClass))) {
            throw new RuntimeException(
                'DatabaseChannel: $notificationClass must implement DatabaseNotificationInterface');
        }

        $notifiable = $event->getNotifiable();
        $notification = $event->getNotification();
        $entityClass = $this->notificationClass;

        /** @var DatabaseNotificationInterface $entity */
        $entity = new $entityClass();
        $entity->setNotifiableId($notifiable->getId());
        $entity->setNotifiableType($notifiable->getNotifiableType());
        $entity->setData($this->getData($notifiable, $notification));

        $notifiable->routeNotificationFor('database', $notification)->add($entity);
        $this->em->persist($entity);
        $this->em->flush();

        $event->setResponse($entity);
    }

    /**
     * Get the data for the notification.
     *
     * @param NotifiableInterface   $notifiable
     * @param NotificationInterface $notification
     *
     * @return array
     * @throws RuntimeException
     */
    protected function getData(NotifiableInterface $notifiable, NotificationInterface $notification): array
    {
        if ($notification instanceof ArrayableInterface) {
            $data = $notification->toArray($notifiable);

            return $data;
        }

        throw new RuntimeException('NotificationInterface must also implement ArrayableInterface.');
    }
}
