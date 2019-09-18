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
use SN\Notifications\Contracts\DatabaseNotificationInterface;

/**
 * Database DatabaseNotification
 *
 * @author Steve Nebes <steve@nebes.net>
 */
class DatabaseNotification implements DatabaseNotificationInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @var mixed
     */
    protected $notifiableId;

    /**
     * @var string
     */
    protected $notifiableType = '';

    /**
     * @var DateTime|null
     */
    protected $readAt;

    /**
     * @var array
     */
    protected $data = [];

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id ?? 0;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getNotifiableId()
    {
        return $this->notifiableId;
    }

    /**
     * @param mixed $notifiableId
     */
    public function setNotifiableId($notifiableId): void
    {
        $this->notifiableId = $notifiableId;
    }

    /**
     * @return string
     */
    public function getNotifiableType(): string
    {
        return $this->notifiableType;
    }

    /**
     * @param string $notifiableType
     */
    public function setNotifiableType(string $notifiableType): void
    {
        $this->notifiableType = $notifiableType;
    }

    /**
     * @return DateTime|null
     */
    public function getReadAt(): ?DateTime
    {
        return $this->readAt;
    }

    /**
     * @param DateTime|null $readAt
     */
    public function setReadAt(?DateTime $readAt): void
    {
        $this->readAt = $readAt;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }
}
