<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Tests\Fixture;

use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Contracts\NotificationInterface;
use SN\Notifications\Email\Email;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class MailFixture implements NotificationInterface
{
    public function via(NotifiableInterface $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(NotifiableInterface $notifiable): Email
    {
        return (new Email())
            ->from('steve@nebes.net')
            ->subject('hello')
            ->text('world');
    }
}
