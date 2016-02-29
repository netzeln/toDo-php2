<?php
    class Task
    {
        private $description;
        private $id;
        private $due;

        function __construct($description, $id = null, $due)
        {
            $this->description = $description;
            $this->id = $id;
            $this->due = $due;
        }

        function setDescription($new_description)
        {
            $this->description = (string) $new_description;
        }

        function getDescription()
        {
            return $this->description;
        }

        function getId()
        {
            return $this->id;
        }


        function getDue()
        {
            return $this->due;
        }

        function setDue($new_due)
        {
            $this->due = $new_due;
        }


        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO tasks (description, due) VALUES ('{$this->getDescription()}',  '{$this->getDue()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks ORDER BY due;");
            $tasks = array();
            foreach($returned_tasks as $task) {
                $description = $task['description'];
                $id = $task['id'];
                $due = $task['due'];
                $new_task = new Task($description, $id, $due);
                array_push($tasks, $new_task);
            }
            return $tasks;
        }

        static function deleteAll()
        {
           $GLOBALS['DB']->exec("DELETE FROM tasks;");
        }

        static function find($search_id)
        {
            $found_task = null;
            $tasks = Task::getAll();
            foreach ($tasks as $task){
                $task_id = $task->getID();
                if ($task_id == $search_id){
                    $found_task = $task;
                }
            }
            return $found_task;
        }

        function update($new_description, $new_due)
        {
            $GLOBALS['DB']->exec("UPDATE tasks SET description ='{$new_description}', due ='{$new_due}' WHERE id = {$this->getID()};");
            $this->setDescription($new_description);
            $this->setDue($new_due);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM tasks WHERE id = {$this->getId()};");
        }

        function addCategory()
        {

        }

        function getCategories()
        {
            
        }

    }
?>
