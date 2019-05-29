<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Events;

use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Contracts\NotificationInterface;
use Symfony\Component\EventDispatcher\Event;

class NotificationFailedEvent extends Event
{
    /**
     * @var NotifiableInterface
     */
    private $notifiable;

    /**
     * @var NotificationInterface
     */
    private $notification;

    /**
     * @var string
     */
    private $channel;

    /**
     * @var array
     */
    private $data = [];

    /**
     * Create instance.
     *
     * @param NotifiableInterface   $notifiable
     * @param NotificationInterface $notification
     * @param string                $channel
     * @param array                 $data
     */
    public function __construct(
        NotifiableInterface $notifiable,
        NotificationInterface $notification,
        string $channel,
        array $data = []
    ) {
        $this->notifiable = $notifiable;
        $this->notification = $notification;
        $this->channel = $channel;
        $this->data = $data;
    }

    /**
     * @return NotifiableInterface
     */
    public function getNotifiable(): NotifiableInterface
    {
        return $this->notifiable;
    }

    /**
     * @return NotificationInterface
     */
    public function getNotification(): NotificationInterface
    {
        return $this->notification;
    }

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }
}
