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
                    array_push($result, array('id' => $message->getId(), 'inbox' => $message->getReceiverId() === $_COOKIE['id'] ? true : false, 'departmental' => $message->isDepartmental(), 'is_read' => $message->isRead(), 'timestamp' => $message->getTimestamp()*1000, 'subject' => $message->getSubject(), 'body' => $message->getBody()));
                }
                return json_encode($result);
            }
        }
        return false;
    }
}