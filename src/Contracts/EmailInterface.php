<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Contracts;

use SN\Notifications\Email\Address;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
interface EmailInterface
{
    /**
     * Set from addresses.
     *
     * @param Address|string ...$addresses
     *
     * @return $this
     */
    public function from(...$addresses): EmailInterface;

    /**
     * Get from addresses.
     *
     * @return Address[]
     */
    public function getFrom(): array;

    /**
     * Set to addresses.
     *
     * @param Address|string ...$addresses
     *
     * @return $this
     */
    public function to(...$addresses): self;

    /**
     * Get to addresses.
     *
     * @return Address[]
     */
    public function getTo(): array;

    /**
     * Set CC addresses.
     *
     * @param Address|string ...$addresses
     *
     * @return $this
     */
    public function cc(...$addresses): self;

    /**
     * Get CC addresses.
     *
     * @return Address[]
     */
    public function getCC(): array;

    /**
     * Get BCC addresses.
     *
     * @param Address|string ...$addresses
     *
     * @return $this
     */
    public function bcc(...$addresses): self;

    /**
     * Set BCC addresses.
     *
     * @return Address[]
     */
    public function getBCC(): array;

    /**
     * Get reply-to addresses.
     *
     * @param Address|string ...$addresses
     *
     * @return $this
     */
    public function replyTo(...$addresses): self;

    /**
     * Set reply-to addresses.
     *
     * @return Address[]
     */
    public function getReplyTo(): array;

    /**
     * Set priority level.
     *
     * @param int $priority
     *
     * @return $this
     */
    public function priority(int $priority): self;

    /**
     * Get priority level.
     *
     * @return int
     */
    public function getPriority(): int;

    /**
     * Set subject text.
     *
     * @param string $subject
     *
     * @return $this
     */
    public function subject(string $subject): self;

    /**
     * Get subject text.
     *
     * @return string
     */
    public function getSubject(): string;

    /**
     * Set text markup.
     *
     * @param string $text
     *
     * @return $this
     */
    public function text(string $text): self;

    /**
     * Get text markup.
     *
     * @return string
     */
    public function getText(): string;

    /**
     * Set HTML template markup.
     *
     * @param string $html
     *
     * @return $this
     */
    public function html(string $html): self;

    /**
     * Get HTML template markup.
     *
     * @return string
     */
    public function getHTML(): string;

    /**
     * Set view data.
     *
     * @param array $data
     *
     * @return EmailInterface
     */
    public function data(array $data): self;

    /**
     * Get view data.
     *
     * @return array
     */
    public function getData(): array;
}
