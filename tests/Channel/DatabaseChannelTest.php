<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Tests\Channel;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SN\Notifications\Channel\DatabaseChannel;
use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Contracts\NotificationInterface;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class DatabaseChannelTest extends TestCase
{
    public function testName(): void
    {
        $channel = new DatabaseChannel();
        $this->assertSame('database', $channel->getName());
    }

    public function testSend(): void
    {
        $notification = new DatabaseNotificationFixture();
        $notifications = new ArrayCollection();

        /** @var MockObject|NotifiableInterface $notifiable */
        $notifiable = $this->createMock(NotifiableInterface::class);
        $notifiable
            ->expects($this->once())
            ->method('routeNotificationFor')
            ->with($this->equalTo('database'), $this->anything())
            ->willReturn($notifications);

        $this->assertCount(0, $notifications);

        $channel = new DatabaseChannel();
        $channel->send($notifiable, $notification);
        $this->assertCount(1, $notifications);

        $entity = $notifications->get(0);
        $this->assertSame(0, $entity->getId());
        $this->assertInstanceOf(\DateTime::class, $entity->getCreatedAt());
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
        /** @var MockObject|NotifiableInterface $notifiable */
        $notifiable = $this->createMock(NotifiableInterface::class);

        $channel = new DatabaseChannel();
        $channel->send($notifiable, $notification);
    }
}

class DatabaseNotificationFixture implements NotificationInterface
{
    public function via(NotifiableInterface $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(NotifiableInterface $notifiable): array
    {
        return ['foo' => 'bar'];
    }
}

class BadDatabaseNotificationFixture implements NotificationInterface
{
    public function via(NotifiableInterface $notifiable): array
    {
        return ['database'];
    }
}
