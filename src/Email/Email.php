<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Email;

use SN\Notifications\Contracts\EmailInterface;

class Email implements EmailInterface
{
    const PRIORITY_HIGHEST = 1;
    const PRIORITY_HIGH    = 2;
    const PRIORITY_NORMAL  = 3;
    const PRIORITY_LOW     = 4;
    const PRIORITY_LOWEST  = 5;

    /**
     * @var Address[]
     */
    private $from = [];

    /**
     * @var Address[]
     */
    private $to = [];

    /**
     * @var Address[]
     */
    private $cc = [];

    /**
     * @var Address[]
     */
    private $bcc = [];

    /**
     * @var Address[]
     */
    private $replyTo = [];

    /**
     * @var int
     */
    private $priority = self::PRIORITY_NORMAL;

    /**
     * @var string
     */
    private $subject = '';

    /**
     * @var string
     */
    private $text = '';

    /**
     * @var string
     */
    private $html = '';

    /**
     * @var array
     */
    private $data = [];

    /**
     * @param Address[]|string[] $addresses
     *
     * @return $this
     */
    public function from(...$addresses): EmailInterface
    {
        $this->from = Address::createArray($addresses);
        return $this;
    }

    /**
     * @return array
     */
    public function getFrom(): array
    {
        return $this->from;
    }

    /**
     * @param Address[]|string[] $addresses
     *
     * @return $this
     */
    public function to(...$addresses): EmailInterface
    {
        $this->to = Address::createArray($addresses);
        return $this;
    }

    /**
     * @return array
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * @param Address[]|string[] $addresses
     *
     * @return $this
     */
    public function cc(...$addresses): EmailInterface
    {
        $this->cc = Address::createArray($addresses);
        return $this;
    }

    /**
     * @return array
     */
    public function getCC(): array
    {
        return $this->cc;
    }

    /**
     * @param Address[]|string[] $addresses
     *
     * @return $this
     */
    public function bcc(...$addresses): EmailInterface
    {
        $this->bcc = Address::createArray($addresses);
        return $this;
    }

    /**
     * @return array
     */
    public function getBCC(): array
    {
        return $this->bcc;
    }

    /**
     * @param Address[]|string[] $addresses
     *
     * @return $this
     */
    public function replyTo(...$addresses): EmailInterface
    {
        $this->replyTo = Address::createArray($addresses);
        return $this;
    }

    /**
     * @return array
     */
    public function getReplyTo(): array
    {
        return $this->replyTo;
    }

    /**
     * @param int $priority
     *
     * @return EmailInterface
     */
    public function priority(int $priority): EmailInterface
    {
        $this->priority = \max(\min($priority, self::PRIORITY_LOWEST), self::PRIORITY_HIGHEST);
        return $this;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param string $subject
     *
     * @return EmailInterface
     */
    public function subject(string $subject): EmailInterface
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $text
     *
     * @return EmailInterface
     */
    public function text(string $text): EmailInterface
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $html
     *
     * @return EmailInterface
     */
    public function html(string $html): EmailInterface
    {
        $this->html = $html;
        return $this;
    }

    /**
     * @return string
     */
    public function getHTML(): string
    {
        return $this->html;
    }

    /**
     * @param array $data
     *
     * @return EmailInterface
     */
    public function data(array $data): EmailInterface
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
