<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Event;

use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Contracts\NotificationInterface;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class NotificationExceptionEvent extends Event
{
    /**
     * @var \Exception
     */
    private $exception;

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
     * Default values.
     *
     * @param \Exception            $exception
     * @param NotifiableInterface   $notifiable
     * @param NotificationInterface $notification
     * @param string                $channel
     */
    public function __construct(
        \Exception $exception,
        NotifiableInterface $notifiable,
        NotificationInterface $notification,
        string $channel
    ) {
        $this->exception = $exception;
        $this->setNotifiable($notifiable);
        $this->setNotification($notification);
        $this->setChannel($channel);
    }

    /**
     * @return \Exception
     */
    public function getException(): \Exception
    {
        return $this->exception;
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
}
