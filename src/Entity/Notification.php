<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(indexes={
 *     @ORM\Index(name="notifiable_idx", columns={"notifiable_type", "notifiable_id"})
 * })
 *
 * @author Steve Nebes <steve@nebes.net>
 */
class Notification
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $notifiableType = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    protected $notifiableId = '';

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $readAt;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    protected $data = [];

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return int
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
     * @return string
     */
    public function getNotifiableId(): string
    {
        return $this->notifiableId;
    }

    /**
     * @param string $notifiableId
     */
    public function setNotifiableId(string $notifiableId): void
    {
        $this->notifiableId = $notifiableId;
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
