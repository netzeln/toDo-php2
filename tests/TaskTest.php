<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Task.php";
    require_once "src/Category.php";

    $server = 'mysql:host=localhost;dbname=to_do_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    class TaskTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Task::deleteAll();
            Category::deleteAll();
        }

        function testSave()
        {
            //Arrange

            $name = "Home stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Wash the dog";
            $due = '2016-02-23';
            $test_task = new Task($description, $id, $due);

            //Act
            $test_task->save();

            //Assert
            $result = Task::getAll();
            $this->assertEquals($test_task, $result[0]);
        }
        function testGetAll()
        {
            //Arrange
            $name = "Home stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Wash the dog";
            $due = '2016-02-23';
            $description2 = "Water the lawn";
            $due2 = '2016-05-23';
            $test_task = new Task($description, $id, $due);
            $test_task->save();
            $test_task2 = new Task($description2, $id,  $due2);
            $test_task2->save();

            //Act
            $result = Task::getAll();

            //Assert
            $this->assertEquals([$test_task, $test_task2], $result);
        }
        function testDeleteAll()
        {
            //Arrange
            $name = "Home stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Wash the dog";
            $due = '2016-02-23';
            $description2 = "Water the lawn";
            $due2 = '2016-05-23';
            $test_task = new Task($description, $id, $due);
            $test_task->save();
            $test_task2 = new Task($description2, $id, $due2);
            $test_task2->save();

            //Act
            Task::deleteAll();

            //Assert
            $result = Task::getAll();
            $this->assertEquals([], $result);
        }

        function testGetId()
        {
            //Arrange

            $name = "Home stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Wash the dog";
            $due = '2016-05-23';
            $test_task = new Task($description, $id, $due);
            $test_task->save();

            //Act
            $result = $test_task->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function testFind()
        {
            //Arrange
            $name = "Home stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Wash the dog";
            $due = '2016-02-23';
            $description2 = "Water the lawn";
            $due2 = '2016-05-23';
            $test_task = new Task($description, $id, $due);
            $test_task->save();
            $test_task2 = new Task($description2, $id, $due2);
            $test_task2->save();

            //Act

            $result = Task::find($test_task->getId());

            //Assert
            $this->assertEquals($test_task, $result);
        }

        function testUpdate()
        {
            $description = "Wash the dog";
            $id = 1;
            $due = '2016-02-23';
            $test_task = new Task($description, $id, $due);
            $test_task->save();

            $new_description = "Wash the Cat";
            $new_due = '2030-06-16';

            //act
            $test_task->update($new_description, $new_due);

            //assert
            $this->assertEquals("Wash the Cat", $test_task->getDescription());
            $this->assertEquals('2030-06-16', $test_task->getDue());

            function testDeleteTask()
            {
                $description = "Wash the dog";
                $due = '2016-02-23';
                $id = 1;
                $test_task = new Task($description, $id, $due);
                $test_task->save();

                $description2 = "Water the lawn";
                $due2 = '2016-05-23';
                $id2 = 2;
                $test_task2 = new Task($description2, $id2, $due2);
                $test_task2->save();

                //act
                $test_task->delete();

                //Assert
                $this->assertEquals([$test_task2], Task::getAll());
            }
        }
    }
 ?>
