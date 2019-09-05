<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Tests;

use PHPUnit\Framework\TestCase;
use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Contracts\NotificationInterface;
use SN\Notifications\Events;
use SN\Notifications\NotificationSender;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as EventDispatcherContract;

class NotificationSenderTest extends TestCase
{
    public function testSend(): void
    {
        $notifiable = $this->createMock(NotifiableInterface::class);
        $notification = new NotificationFixture();

        $dispatcher = $this->createMock(EventDispatcherInterface::class);

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
        $sender->send($notifiable, $notification);
    }
}

class NotificationSenderFixture extends NotificationSender
{
    public function publicDispatch(Events\Event $event, string $eventName): void
    {
        $this->dispatch($event, $eventName);
    }
}

class NotificationFixture implements NotificationInterface
{
    public function via(NotifiableInterface $notifiable): array
    {
        return ['mail'];
    }
}
