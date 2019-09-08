<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Contracts\NotificationInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as EventDispatcherContract;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
class NotificationSender
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * Default values.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Send the given notification to the given notifiable entities.
     *
     * @param NotifiableInterface|NotifiableInterface[] $notifiables
     * @param NotificationInterface                     $notification
     */
    public function send($notifiables, NotificationInterface $notification): void
    {
        $this->sendNow($notifiables, $notification);
    }

    /**
     * Send the given notification immediately.
     *
     * @param NotifiableInterface|NotifiableInterface[] $notifiables
     * @param NotificationInterface                     $notification
     * @param array|null                                $channels
     */
    public function sendNow($notifiables, NotificationInterface $notification, array $channels = null): void
    {
        $notifiables = $this->toNotifiableCollection($notifiables);

        foreach ($notifiables as $notifiable) {
            $viaChannels = $channels ?? $notification->via($notifiable);

            if (empty($viaChannels)) {
                continue;
            }

            foreach ($viaChannels as $channel) {
                $this->sendToNotifiable($notifiable, clone $notification, $channel);
            }
        }
    }

    /**
     * @param NotifiableInterface   $notifiable
     * @param NotificationInterface $notification
     * @param string                $channel
     */
    private function sendToNotifiable(
        NotifiableInterface $notifiable,
        NotificationInterface $notification,
        string $channel
    ): void {
        $event = new Event\NotificationSendingEvent($notifiable, $notification, $channel);
        $this->dispatch($event, NotificationEvents::SENDING);

        if ($event->isPropagationStopped()) {
            return;
        }

        // Send event to channel.
        try {
            $sendEvent = new Event\NotificationSendEvent($notifiable, $notification, $channel);
            $this->dispatch($sendEvent, NotificationEvents::SEND);

            $event = new Event\NotificationSentEvent($notifiable, $notification, $channel, $sendEvent->getResponse());
            $this->dispatch($event, NotificationEvents::SENT);
        } catch (\Exception $exception) {
            $event = new Event\NotificationExceptionEvent($exception, $notifiable, $notification, $channel);
            $this->dispatch($event, NotificationEvents::EXCEPTION);
        }
    }

    /**
     * Event dispatch adapter for Symfony 3.4+ compatibility.
     *
     * @param Event\Event $event
     * @param string|null $eventName
     */
    protected function dispatch(Event\Event $event, string $eventName = null): void
    {
        if ($this->dispatcher instanceof EventDispatcherContract) {
            // Event dispatcher 4.3+
            $this->dispatcher->dispatch($event, $eventName);
        } else {
            // Event dispatcher < 4.3
            $this->dispatcher->dispatch($eventName, $event);
        }
    }

    /**
     * Format $notifiable into an ArrayCollection if necessary.
     *
     * @param NotifiableInterface|NotifiableInterface[] $notifiable
     *
     * @return ArrayCollection|NotifiableInterface[]
     */
    private function toNotifiableCollection($notifiable): ArrayCollection
    {
        if (!$notifiable instanceof Collection && !\is_array($notifiable)) {
            return new ArrayCollection([$notifiable]);
        }

        return $notifiable;
    }
}
