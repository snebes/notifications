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
use SN\Notifications\Email\Email;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class EmailTest extends TestCase
{
    /**
     * @dataProvider methodPermutations
     *
     * @param string $method
     */
    public function testSenders(string $method): void
    {
        $email = new Email();
        $email->from('steve@nebes.net');
        $from = $email->getFrom();

        $this->assertIsArray($from);
        $this->assertCount(1, $from);
        $this->assertInstanceOf(Address::class, $from[0]);
    }

    public function methodPermutations(): iterable
    {
        return [
            'from'    => ['from'],
            'to'      => ['to'],
            'cc'      => ['cc'],
            'bcc'     => ['bcc'],
            'replyTo' => ['replyTo'],
        ];
    }

    public function testPriority(): void
    {
        $email = new Email();
        $email->priority(Email::PRIORITY_HIGHEST);

        $this->assertSame(Email::PRIORITY_HIGHEST, $email->getPriority());
    }

    public function testSubject(): void
    {
        $email = new Email();
        $email->subject('test');

        $this->assertSame('test', $email->getSubject());
    }

    public function testText(): void
    {
        $email = new Email();
        $email->text('test');

        $this->assertSame('test', $email->getText());
    }

    public function testHTML(): void
    {
        $email = new Email();
        $email->html('test');

        $this->assertSame('test', $email->getHTML());
    }

    public function testData(): void
    {
        $email = new Email();
        $email->data(['test']);

        $this->assertSame(['test'], $email->getData());
    }
}
