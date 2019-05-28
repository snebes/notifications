<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications;

use SN\Notifications\Contracts\ChannelInterface;
use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Contracts\NotificationInterface;
use SN\Notifications\Events\NotificationFailedEvent;
use SN\Notifications\Events\NotificationSendingEvent;
use SN\Notifications\Events\NotificationSentEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class NotificationSender
{
    /**
     * @var ChannelInterface[]
     */
    private $channels;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param NotifiableInterface|NotifiableInterface[] $notifiables
     * @param NotificationInterface                     $notification
     */
    public function send($notifiables, NotificationInterface $notification): void
    {
        $this->sendNow($notifiables, $notification);
    }

    /**
     * @param NotifiableInterface|NotifiableInterface[] $notifiables
     * @param mixed                                     $notification
     * @param array                                     $channels
     */
    public function sendNow($notifiables, $notification, array $channels = []): void
    {
        $notifiables = $this->toArray($notifiables);

        foreach ($notifiables as $notifiable) {
            $viaChannels = $channels ?: $notification->via($notifiable);

            if (empty($viaChannels)) {
                continue;
            }

            foreach ((array)$viaChannels as $channel) {
                $this->sendToNotifiable($notifiable, $notification, $channel);
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
        $event = new NotificationSendingEvent($notifiable, $notification, $channel);
        $this->dispatcher->dispatch($event);

        if ($event->isPropagationStopped()) {
            return;
        }

        try {
            $response = $this->getChannel($channel)->send($notifiable, $notification);

            $event = new NotificationSentEvent($notifiable, $notification, $channel, $response);
            $this->dispatcher->dispatch($event);
        } catch (\Exception $e) {
            $event = new NotificationFailedEvent($notifiable, $notification, $channel);
            $this->dispatcher->dispatch($event);
        }
    }

    /**
     * @param string $channel
     * @return ChannelInterface
     *
     * @throws \Exception
     */
    private function getChannel(string $channel): ChannelInterface
    {
        switch ($channel) {
            default:
                break;
        }

        throw new \Exception(\sprintf('Invalid channel %s', $channel));
    }

    /**
     * @param $notifables
     * @return NotifiableInterface[]
     */
    private function toArray($notifables): array
    {
        if (!\is_iterable($notifables)) {
            return [$notifables];
        }

        return $notifables;
    }
}
