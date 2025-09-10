<?php

require_once 'Database.php';

class Notification extends Database
{
    /**
     * Creates a new notification for a specific user.
     * @param int $user_id The ID of the user to notify.
     * @param string $message The notification message.
     * @return bool
     */
    public function createNotification($user_id, $message)
    {
        $sql = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
        return $this->executeNonQuery($sql, [$user_id, $message]);
    }

    /**
     * Retrieves all unread notifications for a specific user.
     * @param int $user_id The ID of the user.
     * @return array
     */
    public function getUnreadNotifications($user_id)
    {
        $sql = "SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    /**
     * Marks a specific notification as read.
     * @param int $notification_id The ID of the notification.
     * @return bool
     */
    public function markAsRead($notification_id)
    {
        $sql = "UPDATE notifications SET is_read = 1 WHERE notification_id = ?";
        return $this->executeNonQuery($sql, [$notification_id]);
    }
}