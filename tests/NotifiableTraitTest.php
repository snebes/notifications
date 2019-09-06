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
use SN\Notifications\Tests\Fixture\NotifiableFixture;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class NotifiableTraitTest extends TestCase
{
    public function testMail(): void
    {
        $instance = new NotifiableFixture();
        $instance->email = 'steve@nebes.net';
        $this->assertSame('steve@nebes.net', $instance->routeNotificationFor('mail'));
    }

    public function testDatabase(): void
    {
        $instance = new NotifiableFixture();
        $this->assertCount(0, $instance->routeNotificationFor('database'));
    }

    public function testFoo(): void
    {
        $instance = new NotifiableFixture();
        $this->assertSame('bar', $instance->routeNotificationFor('foo'));
    }

    public function testUndefined(): void
    {
        $instance = new NotifiableFixture();
        $this->assertNull($instance->routeNotificationFor('baz'));
    }

    public function testDatabaseReadNotifications(): void
    {
        $instance = new NotifiableFixture();
        $this->assertCount(0, $instance->getReadNotifications());
    }

    public function testDatabaseUnreadNotifications(): void
    {
        $instance = new NotifiableFixture();
        $this->assertCount(0, $instance->getUnreadNotifications());
    }
}
