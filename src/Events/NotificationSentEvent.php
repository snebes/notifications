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
use Symfony\Contracts\EventDispatcher\Event;

class NotificationSentEvent extends Event
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
     * @var mixed
     */
    private $response;

    /**
     * Create instance.
     *
     * @param NotifiableInterface   $notifiable
     * @param NotificationInterface $notification
     * @param string                $channel
     * @param mixed                 $response
     */
    public function __construct(
        NotifiableInterface $notifiable,
        NotificationInterface $notification,
        string $channel,
        $response
    ) {
        $this->notifiable = $notifiable;
        $this->notification = $notification;
        $this->channel = $channel;
        $this->response = $response;
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

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }
}
