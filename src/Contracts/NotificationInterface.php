<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Contracts;

/**
 * Methods common to all notifications.
 */
interface NotificationInterface
{
    /**
     * Return list of channels to send notification.
     *
     * @return string[]
     */
    public function via(): array;
}
