<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\SN\Notifications\Channel;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SN\Notifications\Channel\MailChannel;
use SN\Notifications\Contracts\MailerInterface;
use Tests\SN\Notifications\Fixture\BadMailFixture;
use Tests\SN\Notifications\Fixture\MailFixture;
use Tests\SN\Notifications\Fixture\NotifiableFixture;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class MailChannelTest extends TestCase
{
    public function testName(): void
    {
        /** @var MockObject|MailerInterface $mailer */
        $mailer = $this->createMock(MailerInterface::class);

        $channel = new MailChannel($mailer);
        $this->assertSame('mail', $channel->getName());
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
        $response = $channel->send($notifiable, $notification);

        $this->assertTrue($response);
    }

    public function testNoSenderNotification()
    {
        $notification = new MailFixture();
        $notifiable = new NotifiableFixture();
        $notifiable->email = '';

        /** @var MockObject|MailerInterface $mailer */
        $mailer = $this->createMock(MailerInterface::class);

        $channel = new MailChannel($mailer);
        $response = $channel->send($notifiable, $notification);

        $this->assertNull($response);
    }

    public function testBadNotification()
    {
        $this->expectException(\RuntimeException::class);

        $notification = new BadMailFixture();
        $notifiable = new NotifiableFixture();

        /** @var MockObject|MailerInterface $mailer */
        $mailer = $this->createMock(MailerInterface::class);

        $channel = new MailChannel($mailer);
        $channel->send($notifiable, $notification);
    }
}
