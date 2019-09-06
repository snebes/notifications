<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Mailer;

use SN\Notifications\Contracts\EmailInterface;
use SN\Notifications\Contracts\MailerInterface;
use SN\Notifications\Email\Address;
use Swift_Mailer;
use Swift_Message;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class SwiftMailerMailer implements MailerInterface
{
    /**
     * @var Swift_Mailer
     */
    private $swiftMailer;

    /**
     * Default values.
     *
     * @param Swift_Mailer $swiftMailer
     */
    public function __construct(Swift_Mailer $swiftMailer)
    {
        $this->swiftMailer = $swiftMailer;
    }

    /**
     * @param EmailInterface $email
     *
     * @return bool
     */
    public function send($email): bool
    {
        if (!$email instanceof EmailInterface) {
            return false;
        }

        if (empty($email->getTo()) || empty($email->getSubject())) {
            return false;
        }

        // Create Swift Message.
        $swiftMessage = $this->createMessage($email);
        $response = $this->swiftMailer->send($swiftMessage);

        return 0 !== $response;
    }

    /**
     * @param EmailInterface $email
     *
     * @return Swift_Message
     */
    private function createMessage(EmailInterface $email): Swift_Message
    {
        $message = new Swift_Message();
        $message->setFrom($this->formatAddresses($email->getFrom()));
        $message->setTo($this->formatAddresses($email->getTo()));
        $message->setCc($this->formatAddresses($email->getCC()));
        $message->setBcc($this->formatAddresses($email->getBCC()));
        $message->setReplyTo($this->formatAddresses($email->getReplyTo()));
        $message->setSubject($email->getSubject());
        $message->setPriority($email->getPriority());

        if (!empty($email->getHTML())) {
            $message->addPart($email->getHTML(), 'text/html');
        }

        if (!empty($email->getText())) {
            $message->addPart($email->getText(), 'text/plain');
        }

        return $message;
    }

    /**
     * @param Address[] $addresses
     *
     * @return array
     */
    private function formatAddresses(array $addresses): array
    {
        $list = [];

        foreach ($addresses as $address) {
            $list[] = $address->getName() ? [$address->getAddress(), $address->getName()] : $address->getAddress();
        }

        return $list;
    }
}
