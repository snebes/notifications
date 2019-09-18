<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Tests\Channel;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SN\Notifications\Channel\DatabaseChannel;
use SN\Notifications\Contracts\DatabaseNotificationInterface;
use SN\Notifications\Model\DatabaseNotification;
use SN\Notifications\Event\NotificationSendEvent;
use SN\Notifications\NotificationEvents;
use SN\Notifications\Tests\Fixture\BadDatabaseNotificationFixture;
use SN\Notifications\Tests\Fixture\DatabaseNotificationFixture;
use SN\Notifications\Tests\Fixture\NotifiableFixture;
use SN\Notifications\Tests\Fixture\NotificationFixture;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class DatabaseChannelTest extends TestCase
{
    public function testName(): void
    {
        $this->assertSame('database', DatabaseChannel::getName());
    }

    public function testEvents(): void
    {
        $this->assertSame([NotificationEvents::SEND => [['send', 255]]], DatabaseChannel::getSubscribedEvents());
    }

    public function testSend(): void
    {
        $notification = new DatabaseNotificationFixture();
        $notifiable = new NotifiableFixture();
        $notifications = $notifiable->getNotifications();

        $this->assertCount(0, $notifications);

        $channel = $this->getChannel();
        $event = new NotificationSendEvent($notifiable, $notification, DatabaseChannel::getName());
        $channel->send($event);

        $this->assertCount(1, $notifications);

        $entity = $notifications->get(0);

        $this->assertSame($entity, $event->getResponse());
        $this->assertInstanceOf(DatabaseNotification::class, $entity);

        $this->assertSame(0, $entity->getId());
        $this->assertInstanceOf(\DateTime::class, $entity->getCreatedAt());
        $this->assertSame(NotifiableFixture::class, $entity->getNotifiableType());
        $this->assertSame('foo', $entity->getNotifiableId());
        $this->assertSame(['foo' => 'bar'], $entity->getData());
        $this->assertNull($entity->getReadAt());

        $readAt = new \DateTime();
        $entity->setReadAt($readAt);
        $this->assertSame($readAt, $entity->getReadAt());
    }

    public function testBadNotification()
    {
        $this->expectException(\Exception::class);

        $notification = new BadDatabaseNotificationFixture();
        /** @var MockObject|NotifiableFixture $notifiable */
        $notifiable = $this->createMock(NotifiableFixture::class);

        $channel = $this->getChannel();
        $channel->send(new NotificationSendEvent($notifiable, $notification, DatabaseChannel::getName()));
    }

    public function testWrongChannel(): void
    {
        $notifiable = new NotifiableFixture();
        $notification = new NotificationFixture();

        $channel = $this->getChannel();
        $event = new NotificationSendEvent($notifiable, $notification, 'baz');
        $channel->send($event);

        $this->assertNull($event->getResponse());
    }

    public function testNullEntityManager(): void
    {
        $notifiable = new NotifiableFixture();
        $notification = new NotificationFixture();
        $notifications = $notifiable->getNotifications();

        $channel = new DatabaseChannel(null);
        $event = new NotificationSendEvent($notifiable, $notification, DatabaseChannel::getName());
        $channel->send($event);

        $this->assertCount(0, $notifications);
    }

    public function testWrongClass(): void
    {
        $this->expectException(\Exception::class);

        $notifiable = new NotifiableFixture();
        $notification = new NotificationFixture();

        $channel = new DatabaseChannel($this->createMock(EntityManager::class), \StdClass::class);
        $event = new NotificationSendEvent($notifiable, $notification, DatabaseChannel::getName());
        $channel->send($event);
    }

    private function getChannel(): DatabaseChannel
    {
        /** @var MockObject|EntityManager $em */
        $em = $this->createMock(EntityManager::class);
        $em
            ->expects($this->any())
            ->method('persist')
            ->with($this->isInstanceOf(DatabaseNotificationInterface::class));
        $em
            ->expects($this->any())
            ->method('flush');

        return new DatabaseChannel($em, DatabaseNotification::class);
    }
}
