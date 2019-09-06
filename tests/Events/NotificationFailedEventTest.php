<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Tests\Events;

use PHPUnit\Framework\TestCase;
use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Contracts\NotificationInterface;
use SN\Notifications\Events\NotificationFailedEvent;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class NotificationFailedEventTest extends TestCase
{
    public function testEvent(): void
    {
        $exception = new \RuntimeException();
        /** @var NotifiableInterface $notifiable */
        $notifiable = $this->createMock(NotifiableInterface::class);
        /** @var NotificationInterface $notification */
        $notification = $this->createMock(NotificationInterface::class);

        $event = new NotificationFailedEvent($exception, $notifiable, $notification, 'channel');
        $this->assertSame($exception, $event->getException());
        $this->assertSame($notifiable, $event->getNotifiable());
        $this->assertSame($notification, $event->getNotification());
        $this->assertSame('channel', $event->getChannel());
    }
}
