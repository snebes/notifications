<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Contracts;

/**
 * Notifications must implement this interface for channels such as Mail.
 *
 * @author Steve Nebes <steve@nebes.net>
 */
interface MailableInterface
{
    /**
     * Get the mail representation of the notification.
     *
     * @param NotifiableInterface $notifiable
     *
     * @return EmailInterface
     */
    public function toMail(NotifiableInterface $notifiable): EmailInterface;
}
