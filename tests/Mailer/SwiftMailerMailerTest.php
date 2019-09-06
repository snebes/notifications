<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\SN\Notifications\Mailer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SN\Notifications\Email\Address;
use SN\Notifications\Email\Email;
use SN\Notifications\Mailer\SwiftMailerMailer;
use Swift_Mailer;
use Swift_Message;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class SwiftMailerMailerTest extends TestCase
{
    public function testSend(): void
    {
        $email = (new Email())
            ->from(new Address('steve@nebes.net', 'Steve'))
            ->to('jack@example.com')
            ->cc('jill@example.com')
            ->bcc('james@example.com')
            ->replyTo('steve@nebes.net')
            ->priority(Email::PRIORITY_HIGHEST)
            ->subject('Hello')
            ->text('World')
            ->html('<p>World</p>');

        /** @var MockObject|Swift_Mailer $swiftMailer */
        $swiftMailer = $this->createMock(Swift_Mailer::class);
        $swiftMailer
            ->expects($this->once())
            ->method('send')
            ->with($this->callback(function ($message) {
                $this->assertInstanceOf(Swift_Message::class, $message);

                /** @var Swift_Message $message */
                $this->assertSame(['steve@nebes.net' => 'Steve'], $message->getFrom());
                $this->assertSame(['jack@example.com' => null], $message->getTo());
                $this->assertSame(['jill@example.com' => null], $message->getCc());
                $this->assertSame(['james@example.com' => null], $message->getBcc());
                $this->assertSame(['steve@nebes.net' => null], $message->getReplyTo());
                $this->assertEquals(Swift_Message::PRIORITY_HIGHEST, $message->getPriority());
                $this->assertSame('Hello', $message->getSubject());

                return true;
            }))
            ->willReturn(1);

        $mailer = new SwiftMailerMailer($swiftMailer);
        $response = $mailer->send($email);

        $this->assertTrue($response);
    }

    public function testSendBad(): void
    {
        /** @var MockObject|Swift_Mailer $swiftMailer */
        $swiftMailer = $this->createMock(Swift_Mailer::class);

        $mailer = new SwiftMailerMailer($swiftMailer);
        $response = $mailer->send(null);

        $this->assertFalse($response);
    }

    public function testSendEmpty(): void
    {
        /** @var MockObject|Swift_Mailer $swiftMailer */
        $swiftMailer = $this->createMock(Swift_Mailer::class);

        $mailer = new SwiftMailerMailer($swiftMailer);
        $response = $mailer->send(new Email());

        $this->assertFalse($response);
    }
}
