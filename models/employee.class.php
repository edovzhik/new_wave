<?php
require_once('core/database.class.php');
require_once('models/department.class.php');

class Employee
{
    private $id;

    private function __construct($id)
    {
        $this->id = $id;
    }

    public static function create($name, $department_id, $title)
    {
        if (isset($name) and isset($department_id) and isset($title) and strlen($name) < 100 and strlen($title) < 100 and Database::connect() and Department::withId($department_id)) {
            $handle = Database::connect()->prepare('INSERT INTO employees (name, department_id, title) VALUES (?, ?, ?)');
            $handle->bindValue(1, $name);
            $handle->bindValue(2, $department_id, \PDO::PARAM_INT);
            $handle->bindValue(3, $title);
            $handle->execute();
            return Employee::withId(Database::connect()->lastInsertId());
        }
        return false;
    }

    public static function withId($id)
    {
        if (isset($id) and $id > 0 and Database::connect()) {
            $handle = Database::connect()->prepare('SELECT * FROM employees WHERE id = ?');
            $handle->bindValue(1, $id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return new Employee($result->id);
            }
        }
        return false;
    }

    public static function withUsername($username)
    {
        if (isset($username) and $username > 0 and Database::connect()) {
            $handle = Database::connect()->prepare('SELECT * FROM employees WHERE username = ?');
            $handle->bindValue(1, $username, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return new Employee($result->id);
            }
        }
        return false;
    }

    public function getId()
    {
        return isset($this->id) ? $this->id : false;
    }

    public function setName($name)
    {
        if (isset($this->id) and isset($name) and strlen($name) < 100) {
            $handle = Database::connect()->prepare('UPDATE employees SET name = ? WHERE id = ?');
            $handle->bindValue(1, $name);
            $handle->bindValue(2, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            if ($this->getName() === $name) {
                return true;
            }
        }
        return false;
    }

    public function getName()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('SELECT name FROM employees WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return $result->name;
            }
        }
        return false;
    }

    public function setDepartmentId($department_id)
    {
        if (isset($this->id) and isset($department_id) and Department::withId($department_id)) {
            $handle = Database::connect()->prepare('UPDATE employees SET department_id = ? WHERE id = ?');
            $handle->bindValue(1, $department_id, \PDO::PARAM_INT);
            $handle->bindValue(2, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            if ($this->getDepartmentId() === $department_id) {
                return true;
            }
        }
        return false;
    }

    public function getDepartmentId()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('SELECT department_id FROM employees WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return $result->department_id;
            }
        }
        return false;
    }

    public function setTitle($title)
    {
        if (isset($this->id) and isset($title) and strlen($title) < 100) {
            $handle = Database::connect()->prepare('UPDATE employees SET title = ? WHERE id = ?');
            $handle->bindValue(1, $title);
            $handle->bindValue(2, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            if ($this->getTitle() === $title) {
                return true;
            }
        }
        return false;
    }

    public function getTitle()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('SELECT title FROM employees WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return $result->title;
            }
        }
        return false;
    }

    public function setUsername($username)
    {
        if (isset($this->id) and isset($username) and strlen($username) < 20 and preg_match("/^[a-zA-Z0-9]+$/", $username)) {
            $handle = Database::connect()->prepare('UPDATE employees SET username = ? WHERE id = ?');
            $handle->bindValue(1, $username);
            $handle->bindValue(2, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            if ($this->getUsername() === $username) {
                return true;
            }
        }
        return false;
    }

    public function getUsername()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('SELECT username FROM employees WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return $result->username;
            }
        }
        return false;
    }

    public function setPassword($password)
    {
        if (isset($this->id) and isset($password) and strlen($password) < 128) {
            $handle = Database::connect()->prepare('UPDATE employees SET password = ? WHERE id = ?');
            $handle->bindValue(1, $password);
            $handle->bindValue(2, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            if ($this->getPassword() === $password) {
                return true;
            }
        }
        return false;
    }

    public function getPassword()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('SELECT password FROM employees WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return $result->password;
            }
        }
        return false;
    }

    public function setSessionHash($session_hash)
    {
        if (isset($this->id) and isset($session_hash) and strlen($session_hash) < 128) {
            $handle = Database::connect()->prepare('UPDATE employees SET session_hash = ? WHERE id = ?');
            $handle->bindValue(1, $session_hash);
            $handle->bindValue(2, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            if ($this->getSessionHash() === $session_hash) {
                return true;
            }
        }
        return false;
    }

    public function getSessionHash()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('SELECT session_hash FROM employees WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return $result->session_hash;
            }
        }
        return false;
    }

    public function setSalt($salt)
    {
        if (isset($this->id) and isset($salt) and strlen($salt) < 128) {
            $handle = Database::connect()->prepare('UPDATE employees SET salt = ? WHERE id = ?');
            $handle->bindValue(1, $salt);
            $handle->bindValue(2, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            if ($this->getSalt() === $salt) {
                return true;
            }
        }
        return false;
    }

    public function getSalt()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('SELECT salt FROM employees WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return $result->salt;
            }
        }
        return false;
    }

    public function delete()
    {
        if (!isset($this->id)) {
            return true;
        }
        $handle = Database::connect()->prepare('DELETE FROM employees WHERE id = ?');
        $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
        $handle->execute();
        if (!Employee::withId($this->id)) {
            unset($this->id);
            return true;
        } else {
            return false;
        }
    }
}
