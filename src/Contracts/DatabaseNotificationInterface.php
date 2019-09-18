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
 * The DatabaseNotificationInterface defines how a database-stored notification needs to be structured for
 * DatabaseChannel to use it..
 *
 * @author Steve Nebes <steve@nebes.net>
 */
interface DatabaseNotificationInterface
{
    /**
     * Set unique identifier of notifiable.
     *
     * @param mixed $id
     */
    public function setNotifiableId($id);

    /**
     * Set canonical name of notifiable.
     *
     * @param string $type
     */
    public function setNotifiableType(string $type);

    /**
     * Set metadata stored in notification.
     *
     * @param array $data
     */
    public function setData(array $data);
}
