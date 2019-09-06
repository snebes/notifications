<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Tests\Email;

use PHPUnit\Framework\TestCase;
use SN\Notifications\Email\Address;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class AddressTest extends TestCase
{
    public function testCreateByConstructor()
    {
        $address = new Address('steve@nebes.net', 'Steve');
        $this->assertEquals('steve@nebes.net', $address->getAddress());
        $this->assertEquals('Steve', $address->getName());

        $this->assertSame('Steve <steve@nebes.net>', $address->__toString());
    }

    public function testCreate1()
    {
        $address = Address::create('steve@nebes.net');
        $this->assertEquals('steve@nebes.net', $address->getAddress());
        $this->assertEquals('', $address->getName());
    }

    public function testCreate2()
    {
        $address = Address::create(new Address('steve@nebes.net', 'Steve'));
        $this->assertEquals('steve@nebes.net', $address->getAddress());
        $this->assertEquals('Steve', $address->getName());
    }

    public function testException()
    {
        $this->expectException(\InvalidArgumentException::class);

        Address::create(1);
    }
}
