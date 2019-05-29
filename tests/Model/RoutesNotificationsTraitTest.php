<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Model;

use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use SN\Notifications\Channel\DatabaseChannel;

class RoutesNotificationsTraitTest extends TestCase
{
    public function testRouteNotificationFor(): void
    {
        $instance = new RoutesNotificationsInstance();

        $this->assertInstanceOf(Collection::class, $instance->routeNotificationFor(DatabaseChannel::class));
//        $this->assertSame('steve@nebes.net', $instance->routeNotificationFor('mail'));
//        $this->assertSame('555-867-5309', $instance->routeNotificationFor('sms'));
        $this->assertSame('bar', $instance->routeNotificationFor('foo'));
    }
}
