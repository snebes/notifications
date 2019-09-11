<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Model;

use DateTime;

/**
 * The DatabaseNotificationInterface defines how a database-stored notification needs to be structured for
 * DatabaseChannel to use it..
 *
 * @author Steve Nebes <steve@nebes.net>
 */
interface DatabaseNotificationInterface
{
    /**
     * Get the created date/time.
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime;

    /**
     * Get a unique identifier of notifiable, such as the record id.
     *
     * @return string
     */
    public function getNotifiableId(): string;

    /**
     * Set unique identifier of notifiable.
     *
     * @param mixed $id
     */
    public function setNotifiableId($id): void;

    /**
     * Get a canonical name of notifiable, such as the fully-qualified class name.
     *
     * @return string
     */
    public function getNotifiableType(): string;

    /**
     * Set canonical name of notifiable.
     *
     * @param string $type
     */
    public function setNotifiableType(string $type): void;

    /**
     * Get the read at date/time.
     *
     * @return DateTime|null
     */
    public function getReadAt(): ?DateTime;

    /**
     * Set read at date/time.
     *
     * @param DateTime|null $dateTime
     */
    public function setReadAt(?DateTime $dateTime): void;

    /**
     * Get metadata stored in notification.
     *
     * @return mixed
     */
    public function getData();

    /**
     * Set metadata stored in notification.
     *
     * @param $data
     */
    public function setData($data): void;
}
