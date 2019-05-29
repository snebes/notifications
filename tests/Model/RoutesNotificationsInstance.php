<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Model;

use SN\Notifications\Model\NotifiableTrait;

class RoutesNotificationsInstance
{
    use NotifiableTrait;

    /**
     * @var string
     */
    private $email = 'steve@nebes.net';

    /**
     * @var string
     */
    private $phoneNumber = '555-867-5309';

    /**
     * @return string
     */
    public function routeNotificationForFoo(): string
    {
        return 'bar';
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
