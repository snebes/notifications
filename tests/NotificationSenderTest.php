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
use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Event;
use SN\Notifications\NotificationEvents;
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
                ->expects($this->exactly(3))
                ->method('dispatch')
                ->withConsecutive([
                    $this->isInstanceOf(Event\NotificationSendingEvent::class),
                    $this->equalTo(NotificationEvents::SENDING),
                ], [
                    $this->isInstanceOf(Event\NotificationSendEvent::class),
                    $this->equalTo(NotificationEvents::SEND),
                ], [
                    $this->isInstanceOf(Event\NotificationSentEvent::class),
                    $this->equalTo(NotificationEvents::SENT),
                ]);
        } else {
            $dispatcher
                ->expects($this->exactly(3))
                ->method('dispatch')
                ->withConsecutive([
                    $this->equalTo(NotificationEvents::SENDING),
                    $this->isInstanceOf(Event\NotificationSendingEvent::class),
                ], [
                    $this->equalTo(NotificationEvents::SEND),
                    $this->isInstanceOf(Event\NotificationSendEvent::class),
                ], [
                    $this->equalTo(NotificationEvents::SENT),
                    $this->isInstanceOf(Event\NotificationSentEvent::class),
                ]);
        }

        $sender = new NotificationSender($dispatcher);
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
                    $this->callback(function (Event\NotificationSendingEvent $event) {
                        $event->stopPropagation();
                        return true;
                    }),
                    $this->equalTo(NotificationEvents::SENDING));
        } else {
            $dispatcher
                ->expects($this->exactly(1))
                ->method('dispatch')
                ->with(
                    $this->equalTo(NotificationEvents::SENDING),
                    $this->callback(function (Event\NotificationSendingEvent $event) {
                        $event->stopPropagation();
                        return true;
                    }));
        }

        $sender = new NotificationSender($dispatcher);
        $sender->send($notifiable, $notification);
    }

    public function testCatchException(): void
    {
        /** @var NotifiableInterface $notifiable */
        $notifiable = $this->createMock(NotifiableInterface::class);
        $notification = new NotificationFixture();

        /** @var MockObject|EventDispatcherInterface $dispatcher */
        $dispatcher = $this->createMock(EventDispatcherInterface::class);

        if (\interface_exists(EventDispatcherContract::class)) {
            $dispatcher
                ->expects($this->at(0))
                ->method('dispatch')
                ->with($this->isInstanceOf(Event\NotificationSendingEvent::class), $this->equalTo(NotificationEvents::SENDING));

            $dispatcher
                ->expects($this->at(1))
                ->method('dispatch')
                ->with($this->isInstanceOf(Event\NotificationSendEvent::class), $this->equalTo(NotificationEvents::SEND))
                ->will($this->throwException(new \RuntimeException()));

            $dispatcher
                ->expects($this->at(2))
                ->method('dispatch')
                ->with($this->isInstanceOf(Event\NotificationExceptionEvent::class), $this->equalTo(NotificationEvents::EXCEPTION));
        } else {
            $dispatcher
                ->expects($this->at(0))
                ->method('dispatch')
                ->with($this->equalTo(NotificationEvents::SENDING), $this->isInstanceOf(Event\NotificationSendingEvent::class));

            $dispatcher
                ->expects($this->at(1))
                ->method('dispatch')
                ->with($this->equalTo(NotificationEvents::SEND), $this->isInstanceOf(Event\NotificationSendEvent::class))
                ->will($this->throwException(new \RuntimeException()));

            $dispatcher
                ->expects($this->at(2))
                ->method('dispatch')
                ->with($this->equalTo(NotificationEvents::EXCEPTION), $this->isInstanceOf(Event\NotificationExceptionEvent::class));
        }

        $sender = new NotificationSender($dispatcher);
        $sender->send($notifiable, $notification);
    }
}
