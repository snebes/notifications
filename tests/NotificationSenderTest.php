<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\SN\Notifications;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SN\Notifications\Channel\MailChannel;
use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Events;
use SN\Notifications\NotificationSender;
use Tests\SN\Notifications\Fixture\NotificationFixture;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as EventDispatcherContract;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class NotificationSenderTest extends TestCase
{
    public function testSend(): void
    {
        /** @var NotifiableInterface $notifiable */
        $notifiable = $this->createMock(NotifiableInterface::class);
        $notification = new NotificationFixture();

        /** @var MockObject|EventDispatcherInterface $dispatcher */
        $dispatcher = $this->createMock(EventDispatcherInterface::class);

        if (\interface_exists(EventDispatcherContract::class)) {
            $dispatcher
                ->expects($this->exactly(2))
                ->method('dispatch')
                ->withConsecutive([
                    $this->isInstanceOf(Events\NotificationSendingEvent::class),
                    $this->equalTo('sn.notification.sending'),
                ], [
                    $this->isInstanceOf(Events\NotificationSentEvent::class),
                    $this->equalTo('sn.notification.sent'),
                ]);
        } else {
            $dispatcher
                ->expects($this->exactly(2))
                ->method('dispatch')
                ->withConsecutive([
                    $this->equalTo('sn.notification.sending'),
                    $this->isInstanceOf(Events\NotificationSendingEvent::class),
                ], [
                    $this->equalTo('sn.notification.sent'),
                    $this->isInstanceOf(Events\NotificationSentEvent::class),
                ]);
        }

        /** @var MockObject|MailChannel $mailChannel */
        $mailChannel = $this->createMock(MailChannel::class);
        $mailChannel->expects($this->once())->method('getName')->willReturn('mail');

        $sender = new NotificationSender($dispatcher);
        $sender->registerChannel($mailChannel);

        $sender->send($notifiable, $notification);
    }

    public function testSendWithoutChannels(): void
    {
        /** @var NotifiableInterface $notifiable */
        $notifiable = $this->createMock(NotifiableInterface::class);
        $notification = new NotificationFixture();

        /** @var MockObject|EventDispatcherInterface $dispatcher */
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher
            ->expects($this->exactly(0))
            ->method('dispatch');

        $sender = new NotificationSender($dispatcher);
        $sender->sendNow($notifiable, $notification, []);
    }

    public function testSendNotifiableCollection(): void
    {
        /** @var NotifiableInterface $notifiable */
        $notifiable = $this->createMock(NotifiableInterface::class);
        $notification = new NotificationFixture();

        /** @var MockObject|EventDispatcherInterface $dispatcher */
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher
            ->expects($this->exactly(0))
            ->method('dispatch');

        $sender = new NotificationSender($dispatcher);
        $sender->sendNow(new ArrayCollection([$notifiable]), $notification, []);
    }

    public function testSendToFakeChannel(): void
    {
        /** @var NotifiableInterface $notifiable */
        $notifiable = $this->createMock(NotifiableInterface::class);
        $notification = new NotificationFixture();

        /** @var MockObject|EventDispatcherInterface $dispatcher */
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher
            ->expects($this->exactly(2))
            ->method('dispatch');

        if (\interface_exists(EventDispatcherContract::class)) {
            $dispatcher
                ->expects($this->exactly(2))
                ->method('dispatch')
                ->withConsecutive([
                    $this->isInstanceOf(Events\NotificationSendingEvent::class),
                    $this->equalTo('sn.notification.sending'),
                ], [
                    $this->isInstanceOf(Events\NotificationFailedEvent::class),
                    $this->equalTo('sn.notification.failed'),
                ]);
        } else {
            $dispatcher
                ->expects($this->exactly(2))
                ->method('dispatch')
                ->withConsecutive([
                    $this->equalTo('sn.notification.sending'),
                    $this->isInstanceOf(Events\NotificationSendingEvent::class),
                ], [
                    $this->equalTo('sn.notification.failed'),
                    $this->isInstanceOf(Events\NotificationFailedEvent::class),
                ]);
        }

        $sender = new NotificationSender($dispatcher);
        $sender->sendNow($notifiable, $notification, ['fake']);
    }

    public function testStopPropagation(): void
    {
        /** @var NotifiableInterface $notifiable */
        $notifiable = $this->createMock(NotifiableInterface::class);
        $notification = new NotificationFixture();

        /** @var MockObject|EventDispatcherInterface $dispatcher */
        $dispatcher = $this->createMock(EventDispatcherInterface::class);

        if (\interface_exists(EventDispatcherContract::class)) {
            $dispatcher
                ->expects($this->exactly(1))
                ->method('dispatch')
                ->with(
                    $this->callback(function (Events\NotificationSendingEvent $event) {
                        $event->stopPropagation();
                        return true;
                    }),
                    $this->equalTo('sn.notification.sending'));
        } else {
            $dispatcher
                ->expects($this->exactly(1))
                ->method('dispatch')
                ->with(
                    $this->equalTo('sn.notification.sending'),
                    $this->callback(function (Events\NotificationSendingEvent $event) {
                        $event->stopPropagation();
                        return true;
                    }));
        }

        $sender = new NotificationSender($dispatcher);
        $sender->send($notifiable, $notification);
    }
}
