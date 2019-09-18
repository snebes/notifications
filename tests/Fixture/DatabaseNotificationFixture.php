<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Tests\Fixture;

use SN\Notifications\Contracts\ArrayableInterface;
use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Contracts\NotificationInterface;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class DatabaseNotificationFixture implements NotificationInterface, ArrayableInterface
{
    public function via(NotifiableInterface $notifiable): array
    {
        return ['database'];
    }

    public function toArray(NotifiableInterface $notifiable): array
    {
        return ['foo' => 'bar'];
    }
}

