<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Category.php";
    require_once "src/Task.php";

    $server = 'mysql:host=localhost;dbname=to_do_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CategoryTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
          Category::deleteAll();
          Task::deleteAll();
        }

        function test_getName()
        {
            //Arrange
            $name = "Work stuff";
            $test_category2 = new Category($name);

            //Act
            $result = $test_category2->getName();

            //Assert
            $this->assertEquals($name, $result);
        }

        function test_getId()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_category2 = new Category($name, $id);

            //Act
            $result = $test_category2->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function test_save()
        {
            //Arrange
            $name = "Work stuff";
            $test_category2 = new Category($name);
            $test_category2->save();

            //Act
            $result = Category::getAll();

            //Assert
            $this->assertEquals($test_category2, $result[0]);
        }

        function test_getAll()
        {
            //Arrange
            $name = "Work stuff";
            $test_category = new Category($name);
            $test_category->save();

            $name2 = "Home stuff";
            $test_category2 = new Category($name2);
            $test_category2->save();

            //Act
            $result = Category::getAll();

            //Assert
            $this->assertEquals([$test_category, $test_category2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $name = "Wash the dog";
            $test_category = new Category($name);
            $test_category->save();

            $name2 = "Home stuff";
            $test_category2 = new Category($name2);
            $test_category2->save();

            //Act
            Category::deleteAll();
            $result = Category::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $name = "Wash the dog";
            $test_category = new Category($name);
            $test_category->save();

            $name2 = "Home stuff";
            $test_category2 = new Category($name2);
            $test_category2->save();

            //Act
            $result = Category::find($test_category2->getId());

            //Assert
            $this->assertEquals($test_category2, $result);
        }

        function testUpdate()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $new_name = "Job stuff";

            //Act
            $test_category->update($new_name);

            //assert
            $this->assertEquals("Job stuff", $test_category->getName());
        }

        function testDeleteCategory()
        {
            //Arrange
            $name = "Wash the dog";
            $id= 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $name2 = "Wash the Cat";
            $id2= 2;
            $test_category2 = new Category($name2, $id2);
            $test_category2->save();


            //Act

            $test_category->delete();

            //Assert
            $this->assertEquals([$test_category2], Category::getAll());
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
            $due = '2016-12-12';
            $test_task = new Task($description, $id2, $due);
            $test_task->save();

            //Act
            $test_category->addTask($test_task);
            $test_category->delete();

            //Assert
            $this->assertEquals([], $test_task->getCategories());
        }

        function testAddTask()
        {
            //arrange
            $name = "Wash the dog";
            $id= 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Get a Dog";
            $id2 = 2;
            $due = '2025-06-16';
            $test_task = new Task($description, $id2, $due);
            $test_task->save();

            //act
            $test_category->addTask($test_task);

            //assert
            $this->assertEquals($test_category->getTasks(), [$test_task]);
        }

        function testGetTasks()
        {
            //arrange
            $name = "Wash the dog";
            $id= 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Get a Dog";
            $id2 = 2;
            $due = '2025-06-16';
            $test_task = new Task($description, $id2, $due);
            $test_task->save();

            $description2 = "Get dog Soap";
            $id3 = 3;
            $due2 = '2025-06-17';
            $test_task2 = new Task($description2, $id3, $due2);
            $test_task2->save();

            //act
            $test_category->addTask($test_task);
            $test_category->addTask($test_task2);

            //assert
            $this->assertEquals($test_category->getTasks(), [$test_task, $test_task2]);
        }


    }

?>
