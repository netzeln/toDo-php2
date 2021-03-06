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
            $done = 0;
            $test_task = new Task($description, $id, $due, $done);

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
            $done = 0;
            $test_task = new Task($description, $id, $due, $done);
            $test_task->save();
            $test_task2 = new Task($description2, $id,  $due2, $done);
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
            $done = 0;
            $test_task = new Task($description, $id, $due, $done);
            $test_task->save();
            $test_task2 = new Task($description2, $id, $due2, $done);
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
            $done = 0;
            $test_task = new Task($description, $id, $due, $done);
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
            $done = 0;
            $test_task = new Task($description, $id, $due, $done);
            $test_task->save();
            $test_task2 = new Task($description2, $id, $due2, $done);
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
            $done =0;
            $test_task = new Task($description, $id, $due, $done);
            $test_task->save();

            $new_description = "Wash the Cat";
            $new_due = '2030-06-16';

            //act
            $test_task->update($new_description, $new_due);

            //assert
            $this->assertEquals("Wash the Cat", $test_task->getDescription());
            $this->assertEquals('2030-06-16', $test_task->getDue());
        }
        function testDeleteTask()
        {
            // $name = "Home Stuff";
            // $id3 = 3;
            // $test_category = new Category($name, $id3);
            // $test_category->save();

            $description = "Wash the dog";
            $due = '2016-02-23';
            $id = 1;
            $done = 0;
            $test_task = new Task($description, $id, $due, $done);
            $test_task->save();

            $description2 = "Water the lawn";
            $due2 = '2016-05-23';
            $id2 = 2;
            $test_task2 = new Task($description2, $id2, $due2, $done);
            $test_task2->save();

            //act

            $test_task->delete();

            //Assert
            $this->assertEquals([$test_task2], Task::getAll());
        }

        function testDelete()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "File reports";
            $id2 = 2;
            $due = '2017-01-01';
            $done = 0;
            $test_task = new Task($description, $id2, $due, $done);
            $test_task->save();

            //Act
            $test_task->addCategory($test_category);
            $test_task->delete();

            //Assert
            $this->assertEquals([], $test_category->getTasks());
        }


        function testAddCategory()
        {
            //arrange
            $name = "Wash the dog";
            $id= 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Get a Dog";
            $id2 = 2;
            $due = '2025-06-16';
            $done = 0;
            $test_task = new Task($description, $id2, $due, $done);
            $test_task->save();

            //act
            $test_task->addCategory($test_category);

            //assert
            $this->assertEquals($test_task->getCategories(), [$test_category]);
        }

        function testGetCategories()
        {
            //arrange
            $name = "Wash the dog";
            $id= 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $name2 = "Wash Self";
            $id2= 2;
            $test_category2 = new Category($name2, $id2);
            $test_category2->save();


            $description2 = "Get soap";
            $id3 = 3;
            $due2 = '2025-06-17';
            $done = 0;
            $test_task = new Task($description2, $id3, $due2, $done);
            $test_task->save();

            //act
            $test_task->addCategory($test_category);
            $test_task->addCategory($test_category2);

            //assert
            $this->assertEquals($test_task->getCategories(), [$test_category, $test_category2]);
        }

        function testMarkDone()
        {
            $description = "Get soap";
            $id = 3;
            $due = '2025-06-17';
            $done = 0;
            $test_task = new Task($description, $id, $due, $done);
            $test_task->save();


            //act
            $test_task->markDone();

            //assert
            $this->assertEquals(TRUE, $test_task->getDone());


        }
    }
 ?>
