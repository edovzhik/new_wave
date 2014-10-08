<?php
require_once('core/database.class.php');

class Department
{
    private $id;

    private function __construct($id)
    {
        $this->id = $id;
    }

    public static function create($department_name)
    {
        if (isset($department_name) and strlen($department_name) < 100 and Database::connect()) {
            $handle = Database::connect()->prepare('INSERT INTO departments (department_name) VALUES (?)');
            $handle->bindValue(1, $department_name);
            $handle->execute();
            return Department::withId(Database::connect()->lastInsertId());
        }
        return false;
    }

    public static function withId($id)
    {
        if (isset($id) and $id > 0 and Database::connect()) {
            $handle = Database::connect()->prepare('SELECT * FROM departments WHERE id = ?');
            $handle->bindValue(1, $id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return new Department($result->id);
            }
        }
        return false;
    }

    public static function getAllDepartments()
    {
        if (Database::connect()) {
            $handle = Database::connect()->prepare('SELECT * FROM departments');
            $handle->execute();
            $result = $handle->fetchAll(\PDO::FETCH_OBJ);
            if ($result) {
                $all_departments = array();
                foreach ($result as $row) {
                    array_push($all_departments, new Department($row->id));
                }
                return $all_departments;
            }
        }
        return false;
    }

    public function delete()
    {
        if (!isset($this->id)) {
            return true;
        }
        $handle = Database::connect()->prepare('DELETE FROM departments WHERE id = ?');
        $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
        $handle->execute();
        if (!Department::withId($this->id)) {
            unset($this->id);
            return true;
        } else {
            return false;
        }
    }

    public function setName($department_name)
    {
        if (isset($this->id) and isset($department_name) and strlen($department_name) < 100) {
            $handle = Database::connect()->prepare('UPDATE departments SET department_name = ? WHERE id = ?');
            $handle->bindValue(1, $department_name);
            $handle->bindValue(2, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            if ($this->getName() === $department_name) {
                return true;
            }
        }
        return false;
    }

    public function getName()
    {
        if (isset($this->id)) {
            $handle = Database::connect()->prepare('SELECT department_name FROM departments WHERE id = ?');
            $handle->bindValue(1, $this->id, \PDO::PARAM_INT);
            $handle->execute();
            $result = $handle->fetch(\PDO::FETCH_OBJ);
            if ($result) {
                return $result->department_name;
            }
        }
        return false;
    }

    public function getId()
    {
        return isset($this->id) ? $this->id : false;
    }
}