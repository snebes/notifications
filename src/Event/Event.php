<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Event;

use Symfony\Component\EventDispatcher\Event as LegacyEvent;
use Symfony\Contracts\EventDispatcher\Event as ContractsEvent;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
if (\class_exists(ContractsEvent::class)) {
    abstract class Event extends ContractsEvent
    {
    }
} else {
    abstract class Event extends LegacyEvent
    {
    }
}
