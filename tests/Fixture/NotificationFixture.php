<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Tests\Fixture;

use SN\Notifications\Contracts\EmailInterface;
use SN\Notifications\Contracts\MailableInterface;
use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Contracts\NotificationInterface;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class NotificationFixture implements NotificationInterface, MailableInterface
{
    public function via(NotifiableInterface $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(NotifiableInterface $notifiable): EmailInterface
    {
        throw new \Exception();
    }
}
