<?php
require_once('models/message.class.php');

abstract class Ajax
{
    public static function retrieveMessages()
    {
        if (isset($_COOKIE['id']) and isset($_POST['contact_id'])) {
            $messages = Message::getCorrespondenceBetween($_COOKIE['id'], $_POST['contact_id']);
            if ($messages) {
                $result = array();
                foreach ($messages as $message) {
                    array_push($result, array('id' => $message->getId(), 'inbox' => $message->getReceiverId() === $_COOKIE['id'] ? 1 : 0, 'is_departmental' => $message->isDepartmental(), 'is_read' => $message->getReceiverId() === $_COOKIE['id'] ? $message->isRead() : 1, 'timestamp' => $message->getTimestamp() * 1000, 'subject' => $message->getSubject(), 'body' => $message->getBody()));
                }
                return json_encode($result);
            }
        }
        return false;
    }

    public static function markAsRead()
    {
        if (isset($_POST['message_id'])) {
            $message = Message::withId($_POST['message_id']);
            if ($message) {
                return $message->markAsRead();
            }
        }
        return false;
    }

    public static function  getEmployeesWithUnread()
    {
        if (isset($_COOKIE['id']))
        {
            return json_encode(Message::getUnreadForEmployeeWithId($_COOKIE['id']));
        }
        return false;
    }

    public static function sendMessage()
    {
        if (isset($_COOKIE['id']) and isset($_POST['receiver_id']) and isset($_POST['subject']) and isset($_POST['body']) and isset($_POST['is_departmental']) and $_POST['is_departmental'] ? Department::withId($_POST['receiver_id']) : Employee::withId($_POST['receiver_id'])) {
            $result = true;
            if ($_POST['is_departmental']) {
                $receivers = Employee::getAllEmployeesFromDepartmentWithId($_POST['receiver_id']);
                foreach ($receivers as $receiver) {
                    $result = ($result and Message::create($_POST['subject'], $_POST['body'], $_COOKIE['id'], $receiver->getId(), true));
                }
            } else {
                $result = ($result and Message::create($_POST['subject'], $_POST['body'], $_COOKIE['id'], $_POST['receiver_id'], false));
            }
            return $result;
        }
        return false;
    }
}