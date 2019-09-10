<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Tests\Channel;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SN\Notifications\Channel\MailChannel;
use SN\Notifications\Contracts\MailerInterface;
use SN\Notifications\Event\NotificationSendEvent;
use SN\Notifications\NotificationEvents;
use SN\Notifications\Tests\Fixture\BadMailFixture;
use SN\Notifications\Tests\Fixture\MailFixture;
use SN\Notifications\Tests\Fixture\NotifiableFixture;
use SN\Notifications\Tests\Fixture\NotificationFixture;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class MailChannelTest extends TestCase
{
    public function testName(): void
    {
        $this->assertSame('mail', MailChannel::getName());
    }

    public function testEvents(): void
    {
        $this->assertSame([NotificationEvents::SEND => [['send', 255]]], MailChannel::getSubscribedEvents());
    }

    public function testSend(): void
    {
        $notification = new MailFixture();
        $notifiable = new NotifiableFixture();
        $notifiable->email = 'foo@bar.baz';

        /** @var MockObject|MailerInterface $mailer */
        $mailer = $this->createMock(MailerInterface::class);
        $mailer
            ->expects($this->once())
            ->method('send')
            ->willReturn(true);

        $channel = new MailChannel($mailer);
        $event = new NotificationSendEvent($notifiable, $notification, MailChannel::getName());
        $channel->send($event);

        $this->assertTrue($event->getResponse());
    }

    public function testNoSenderNotification()
    {
        $this->expectException(\RuntimeException::class);

        $notification = new MailFixture();
        $notifiable = new NotifiableFixture();
        $notifiable->email = '';

        /** @var MockObject|MailerInterface $mailer */
        $mailer = $this->createMock(MailerInterface::class);

        $channel = new MailChannel($mailer);
        $event = new NotificationSendEvent($notifiable, $notification, MailChannel::getName());
        $channel->send($event);

        $this->assertNull($event->getResponse());
    }

    public function testBadNotification1()
    {
        $this->expectException(\RuntimeException::class);

        $notifiable = new NotifiableFixture();
        $notification = new BadMailFixture();

        /** @var MockObject|MailerInterface $mailer */
        $mailer = $this->createMock(MailerInterface::class);

        $channel = new MailChannel($mailer);
        $event = new NotificationSendEvent($notifiable, $notification, MailChannel::getName());
        $channel->send($event);
    }

    public function testBadNotification2()
    {
        $this->expectException(\RuntimeException::class);

        $notifiable = new NotifiableFixture();
        $notification = new NotificationFixture();

        /** @var MockObject|MailerInterface $mailer */
        $mailer = $this->createMock(MailerInterface::class);

        $channel = new MailChannel($mailer);
        $event = new NotificationSendEvent($notifiable, $notification, MailChannel::getName());
        $channel->send($event);
    }

    public function testWrongChannel(): void
    {
        $notifiable = new NotifiableFixture();
        $notification = new NotificationFixture();

        /** @var MockObject|MailerInterface $mailer */
        $mailer = $this->createMock(MailerInterface::class);

        $channel = new MailChannel($mailer);
        $event = new NotificationSendEvent($notifiable, $notification, 'baz');
        $channel->send($event);

        $this->assertNull($event->getResponse());
    }
}
