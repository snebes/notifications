<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\SN\Notifications\Event;

use PHPUnit\Framework\TestCase;
use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Contracts\NotificationInterface;
use SN\Notifications\Event\NotificationSendEvent;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class NotificationSendEventTest extends TestCase
{
    public function testEvent(): void
    {
        /** @var NotifiableInterface $notifiable */
        $notifiable = $this->createMock(NotifiableInterface::class);
        /** @var NotificationInterface $notification */
        $notification = $this->createMock(NotificationInterface::class);

        $event = new NotificationSendEvent($notifiable, $notification, 'channel');
        $this->assertSame($notifiable, $event->getNotifiable());
        $this->assertSame($notification, $event->getNotification());
        $this->assertSame('channel', $event->getChannel());
        $this->assertSame(null, $event->getResponse());
    }
}
