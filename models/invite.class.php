<?php
require_once('core/database.class.php');
require_once('models/employee.class.php');
require_once('helpers.php');

class Invite
{
    private $invite_code;

    private function __construct($invite_code)
    {
        $this->invite_code = $invite_code;
    }

    public static function create($employee_id)
    {
        if (isset($employee_id) and Employee::withId($employee_id) and !Invite::forEmployeeWithId($employee_id)) {
            $handle = Database::connect()->prepare('INSERT INTO invites (invite_code, employee_id, is_used) VALUES (?, ?, 0)');
            $handle->bindValue(1, generateRandomString());
            $handle->bindValue(2, $employee_id, \PDO::PARAM_INT);
            $handle->execute();
            return Invite::forEmployeeWithId($employee_id);
        }
        return false;
    }

    public static function forEmployeeWithId($id)
    {
        if (isset($id) and Employee::withId($id)) {
            $handle = Database::connect()->prepare('SELECT * FROM invites WHERE employee_id = ?');
            $handle->bindValue(1, $id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return new Invite($result->invite_code);
            }
        }
        return false;
    }

    public function delete()
    {
        if (!isset($this->invite_code)) {
            return true;
        }
        $handle = Database::connect()->prepare('DELETE FROM invites WHERE invite_code = ?');
        $handle->bindValue(1, $this->invite_code);
        $handle->execute();
        if (!Invite::withCode($this->invite_code)) {
            unset($this->invite_code);
            return true;
        } else {
            return false;
        }
    }

    public static function withCode($invite_code)
    {
        if (isset($invite_code) and Database::connect()) {
            $handle = Database::connect()->prepare('SELECT * FROM invites WHERE invite_code = ?');
            $handle->bindValue(1, $invite_code);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return new Invite($result->invite_code);
            }
        }
        return false;
    }

    public function getInviteCode()
    {
        return isset($this->invite_code) ? $this->invite_code : false;
    }

    public function setEmployeeId($employee_id)
    {
        if (isset($this->invite_code) and isset($employee_id) and Employee::withId($employee_id) and !Invite::forEmployeeWithId($employee_id)) {
            $handle = Database::connect()->prepare('UPDATE invites SET $employee_id = ? WHERE invite_code = ?');
            $handle->bindValue(1, $employee_id, \PDO::PARAM_INT);
            $handle->bindValue(2, $this->invite_code);
            $handle->execute();
            if ($this->getEmployeeId() === $employee_id) {
                return true;
            }
        }
        return false;
    }

    public function getEmployeeId()
    {
        if (isset($this->invite_code)) {
            $handle = Database::connect()->prepare('SELECT employee_id FROM invites WHERE invite_code = ?');
            $handle->bindValue(1, $this->invite_code);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return $result->emlpoyee_id;
            }
        }
        return false;
    }

    public function markAsUsed()
    {
        if (isset($this->invite_code)) {
            $handle = Database::connect()->prepare('UPDATE invites SET is_used = 1 WHERE invite_code = ?');
            $handle->bindValue(1, $this->invite_code);
            $handle->execute();
            if ($this->isUsed() === 1) {
                return true;
            }
        }
        return false;
    }

    public function isUsed()
    {
        if (isset($this->invite_code)) {
            $handle = Database::connect()->prepare('SELECT is_used FROM invites WHERE invite_code = ?');
            $handle->bindValue(1, $this->invite_code);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return $result->is_used;
            }
        }
        return false;
    }

    public function markAsNotUsed()
    {
        if (isset($this->invite_code)) {
            $handle = Database::connect()->prepare('UPDATE invites SET is_used = 1 WHERE invite_code = ?');
            $handle->bindValue(1, $this->invite_code);
            $handle->execute();
            if ($this->isUsed() === 1) {
                return true;
            }
        }
        return false;
    }
}