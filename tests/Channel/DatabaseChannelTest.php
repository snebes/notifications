<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\SN\Notifications\Channel;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SN\Notifications\Channel\DatabaseChannel;
use SN\Notifications\Entity\Notification;
use SN\Notifications\Event\NotificationSendEvent;
use SN\Notifications\NotificationEvents;
use Tests\SN\Notifications\Fixture\BadDatabaseNotificationFixture;
use Tests\SN\Notifications\Fixture\DatabaseNotificationFixture;
use Tests\SN\Notifications\Fixture\NotifiableFixture;
use Tests\SN\Notifications\Fixture\NotificationFixture;

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

        $channel = new DatabaseChannel();
        $event = new NotificationSendEvent($notifiable, $notification, DatabaseChannel::getName());
        $channel->send($event);

        $this->assertCount(1, $notifications);

        $entity = $notifications->get(0);

        $this->assertSame($entity, $event->getResponse());
        $this->assertInstanceOf(Notification::class, $entity);
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
        $this->expectException(\RuntimeException::class);

        $notification = new BadDatabaseNotificationFixture();
        /** @var MockObject|NotifiableFixture $notifiable */
        $notifiable = $this->createMock(NotifiableFixture::class);

        $channel = new DatabaseChannel();
        $channel->send(new NotificationSendEvent($notifiable, $notification, DatabaseChannel::getName()));
    }

    public function testWrongChannel(): void
    {
        $notifiable = new NotifiableFixture();
        $notification = new NotificationFixture();

        $channel = new DatabaseChannel();
        $event = new NotificationSendEvent($notifiable, $notification, 'baz');
        $channel->send($event);

        $this->assertNull($event->getResponse());
    }
}
