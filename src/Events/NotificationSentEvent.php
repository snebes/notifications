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
     * Default values.
     *
     * @param NotifiableInterface   $notifiable
     * @param NotificationInterface $notification
     * @param string                $channel
     * @param mixed|null            $response
     */
    public function __construct(
        NotifiableInterface $notifiable,
        NotificationInterface $notification,
        string $channel,
        $response = null
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
     * @param NotifiableInterface $notifiable
     */
    public function setNotifiable(NotifiableInterface $notifiable): void
    {
        $this->notifiable = $notifiable;
    }

    /**
     * @return NotificationInterface
     */
    public function getNotification(): NotificationInterface
    {
        return $this->notification;
    }

    /**
     * @param NotificationInterface $notification
     */
    public function setNotification(NotificationInterface $notification): void
    {
        $this->notification = $notification;
    }

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @param string $channel
     */
    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response): void
    {
        $this->response = $response;
    }
}
