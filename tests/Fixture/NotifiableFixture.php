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
use SN\Notifications\NotifiableTrait;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class NotifiableFixture implements NotifiableInterface
{
    use NotifiableTrait;

    public $id = 'foo';
    public $email;
    public $notifications;

    public function routeNotificationForFoo()
    {
        return 'bar';
    }
}
