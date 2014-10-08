<?php

class Message
{

    private $id;

    private function __construct($id)
    {
        $this->id = $id;
    }

    public static function create($subject, $body, $sender_id, $receiver_id, $is_departmental)
    {
        if (isset($subject) and isset($body) and isset($sender_id) and isset($receiver_id) and isset($is_departmental) and strlen($subject) < 256 and strlen($body) < 65000 and $receiver_id != $sender_id and Employee::withId($sender_id) and Employee::withId($receiver_id)) {
            $handle = Database::connect()->prepare('INSERT INTO messages (subject, body, sender_id, receiver_id, is_departmental, is_read) VALUES (?, ?, ?, ?, ?, 0)');
            $handle->bindValue(1, htmlentities($subject, ENT_QUOTES, 'UTF-8'));
            $handle->bindValue(2, htmlentities($body, ENT_QUOTES, 'UTF-8'));
            $handle->bindValue(3, $sender_id, \PDO::PARAM_INT);
            $handle->bindValue(4, $receiver_id, \PDO::PARAM_INT);
            $handle->bindValue(5, $is_departmental ? 1 : 0, \PDO::PARAM_INT);
            $handle->execute();
            return Message::withId(Database::connect()->lastInsertId());
        }
        return false;
    }

    public static function withId($id)
    {
        if (isset($id) and $id > 0 and Database::connect()) {
            $handle = Database::connect()->prepare('SELECT * FROM messages WHERE id = ?');
            $handle->bindValue(1, $id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return new Message($result->id);
            }
        }
        return false;
    }

    public static function getCorrespondenceBetween($employee1_id, $employee2_id)
    {
        if (isset($employee1_id) and isset($employee2_id) and $employee1_id != $employee2_id and Employee::withId($employee1_id) and Employee::withId($employee2_id)) {
            $handle = Database::connect()->prepare('SELECT * FROM messages WHERE ((sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)) ORDER BY timestamp DESC');
            $handle->bindValue(1, $employee1_id, \PDO::PARAM_INT);
            $handle->bindValue(2, $employee2_id, \PDO::PARAM_INT);
            $handle->bindValue(3, $employee2_id, \PDO::PARAM_INT);
            $handle->bindValue(4, $employee1_id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetchAll(\PDO::FETCH_OBJ);
            if ($result) {
                $messages = array();
                foreach ($result as $row) {
                    array_push($messages, new Message($row->id));
                }
                return $messages;
            }
        }
        return false;
    }

    public function getId()
    {
        return isset($this->id) ? $this->id : false;
    }

    public function delete()
    {
        if (!isset($this->id)) {
            return true;
        }
        $handle = Database::connect()->prepare('DELETE FROM messages WHERE id = ?');
        $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
        $handle->execute();
        if (!Message::withId($this->id)) {
            unset($this->id);
            return true;
        } else {
            return false;
        }
    }

    public function getTimestamp()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('SELECT timestamp FROM messages WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                date_default_timezone_set('UTC');
                return strtotime($result->timestamp);
            }
        }
        return false;
    }

    public function setSubject($subject)
    {
        if (isset($this->id) and isset($subject) and strlen($subject) < 256) {
            $handle = Database::connect()->prepare('UPDATE messages SET subject = ? WHERE id = ?');
            $handle->bindValue(1, $subject);
            $handle->bindValue(2, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            if ($this->getSubject() === $subject) {
                return true;
            }
        }
        return false;
    }

    public function getSubject()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('SELECT subject FROM messages WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return $result->subject;
            }
        }
        return false;
    }

    public function setBody($body)
    {
        if (isset($this->id) and isset($body) and strlen($body) < 65000) {
            $handle = Database::connect()->prepare('UPDATE messages SET body = ? WHERE id = ?');
            $handle->bindValue(1, $body);
            $handle->bindValue(2, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            if ($this->getBody() === $body) {
                return true;
            }
        }
        return false;
    }

    public function getBody()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('SELECT body FROM messages WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return $result->body;
            }
        }
        return false;
    }

    public function setSenderId($sender_id)
    {
        if (isset($this->id) and isset($sender_id) and Employee::withId($sender_id)) {
            $handle = Database::connect()->prepare('UPDATE messages SET sender_id = ? WHERE id = ?');
            $handle->bindValue(1, $sender_id);
            $handle->bindValue(2, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            if ($this->getSenderId() === $sender_id) {
                return true;
            }
        }
        return false;
    }

    public function getSenderId()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('SELECT sender_id FROM messages WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return $result->sender_id;
            }
        }
        return false;
    }

    public function setReceiverId($receiver_id)
    {
        if (isset($this->id) and isset($receiver_id) and Employee::withId($receiver_id)) {
            $handle = Database::connect()->prepare('UPDATE messages SET receiver_id = ? WHERE id = ?');
            $handle->bindValue(1, $receiver_id);
            $handle->bindValue(2, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            if ($this->getReceiverId() === $receiver_id) {
                return true;
            }
        }
        return false;
    }

    public function getReceiverId()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('SELECT receiver_id FROM messages WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return $result->receiver_id;
            }
        }
        return false;
    }

    public function markAsDepartmental()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('UPDATE messages SET is_departmental = 1 WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            if ($this->isDepartmental() === 1) {
                return true;
            }
        }
        return false;
    }

    public function isDepartmental()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('SELECT is_departmental FROM messages WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return $result->is_departmental;
            }
        }
        return false;
    }

    public function markAsRead()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('UPDATE messages SET is_read = 1 WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            if ($this->isRead() === 1) {
                return true;
            }
        }
        return false;
    }

    public function isRead()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('SELECT is_read FROM messages WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return $result->is_read;
            }
        }
        return false;
    }

    public function markAsNonDepartmental()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('UPDATE messages SET is_departmental = 0 WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            if ($this->isDepartmental() !== 1) {
                return true;
            }
        }
        return false;
    }

    public function markAsUnread()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('UPDATE messages SET is_read = 0 WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            if ($this->isRead() !== 1) {
                return true;
            }
        }
        return false;
    }
}