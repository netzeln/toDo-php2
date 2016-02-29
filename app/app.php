<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Task.php";
    require_once __DIR__."/../src/Category.php";

    $app = new Silex\Application();

    $server = 'mysql:host=localhost;dbname=to_do';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig', array('categories'=> Category::getAll()));
    });

    $app->get("/tasks", function() use ($app) {
        return $app['twig']->render('tasks.html.twig', array('tasks' => Task::getAll(), 'categories' => Category::getAll()));
    });

    $app->get("/tasks/{id}", function($id) use ($app)
    {
        $task = Task::find($id);
        return $app['twig']->render('task.html.twig', array('task'=>$task, 'categories'=>$task->getCategories(), 'all_categories'=>Category::getAll()));
    });

    $app->get("/categories/{id}", function($id) use ($app){
        $category = Category::find($id);
        return $app['twig']->render('categories.html.twig', array('category'=> $category, 'tasks' => $category->getTasks()));
    });

    $app->get("/total", function() use ($app){
        $category = Category::getAll();
        $tasks = Task::getAll();
        return $app['twig']->render('total.html.twig', array('categories'=> $category, 'tasks' => $tasks));
    });

    $app->post("/tasks", function() use ($app) {

        $description = $_POST['description'];

        $due = $_POST['due'];
        $task = new Task($description, $id = null, $due);
        $task->save();
        // $category = Category::find($category_id);

        return $app['twig']->render('categories.html.twig', array('category' => $category, 'tasks' => Task::getAll()));
    });

    $app->post("/categories", function() use ($app){
        $category = new Category($_POST['name']);
        $category->save();
        var_dump($category);
        return $app['twig']->render('index.html.twig', array('categories'=> Category::getAll()));
    });

    $app->post("/add_tasks", function() use ($app)
    {
        $category = Category::find($_POST['category_id']);
        $task = Task::find($_POST['task_id']);
        $category->addTask($task);
        return $app['twig']->render('category.html.twig', array('category'=>$category, 'categories'=>Category::getAll(), 'tasks'=>$category->getTasks(), 'all_tasks'=> Task::getAll()));

    });

    $app->post("/add_categories", function() use ($app)
    {
        $category = Category::find($_POST['category_id']);
        $task = Task::find($_POST['task_id']);
        $task->addCategory($category);
        return $app['twig']->render('task.html.twig', array('task'=>$task, 'tasks'=>Task::getAll(), 'categories'=> $task->getCategories(), 'all_categories'=>Category::getAll()));
    });

    $app->post("/delete_tasks", function() use ($app) {
        Task::deleteAll();
        return $app['twig']->render('index.html.twig');
    });
    $app->post("/delete_categories", function() use ($app) {
        Category::deleteAll();
        return $app['twig']->render('index.html.twig');
    });


    return $app;
?>
