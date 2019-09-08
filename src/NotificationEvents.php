<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications;

/**
 * Contains all events thrown in the Notification component.
 *
 * @author Steve Nebes <steve@nebes.net>
 */
final class NotificationEvents
{
    /**
     * The SENDING event occurs at the beginning of notification dispatching.
     *
     * This event allows you to modify the notification details before any other component code is executed.
     *
     * @Event("SN\Notifications\Event\NotificationSendingEvent")
     */
    const SENDING = 'sn.notifications.sending';

    /**
     * The SEND event occurs while the notification is being dispatched to individual ChannelInterface handlers.
     *
     * This event is used to send notifications to ChannelInterface handlers. If creating your own channels, this is
     * the event to listen for.
     *
     * @Event("SN\Notifications\Event\NotificationSendEvent")
     */
    const SEND = 'sn.notifications.send';

    /**
     * The SENT event occurs after the notification has been dispatched.
     *
     * This event allows your to check the response from the channel (if any), and take action as needed.
     *
     * @Event("SN\Notifications\Event\NotificationSentEvent")
     */
    const SENT = 'sn.notifications.sent';

    /**
     * The EXCEPTION event occurs when an uncaught exception appears.
     *
     * This event allows you to handle a thrown exception or modify the thrown exception.
     *
     * @Event("SN\Notifications\Event\NotificationExceptionEvent")
     */
    const EXCEPTION = 'sn.notifications.exception';
}
