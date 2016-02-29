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

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig', array('categories'=> Category::getAll()));
    });

    $app->get("/tasks", function() use ($app) {
        return $app['twig']->render('tasks.html.twig', array('tasks' => Task::getAll(), 'categories' => Category::getAll()));
    });

    $app->get("/categories", function() use ($app) {
        return $app['twig']->render('categories.html.twig', array('tasks' => Task::getAll(), 'categories' => Category::getAll()));
    });

    $app->get("/task/{id}", function($id) use ($app)
    {
        $task = Task::find($id);
        return $app['twig']->render('task.html.twig', array('task'=>$task, 'categories'=>$task->getCategories(), 'all_categories'=>Category::getAll()));
    });

    $app->get("/task/{id}/edit", function($id) use ($app)
    {
        $task = Task::find($id);
        return $app['twig']->render('task_edit.html.twig', array('task'=>$task));
    });

    $app->get("/categories/{id}", function($id) use ($app){
        $category = Category::find($id);
        return $app['twig']->render('category.html.twig', array('category'=> $category, 'tasks' => $category->getTasks(), 'all_tasks'=>Task::getAll()));
    });

    $app->get("/categories/{id}/edit", function($id) use ($app){
        $category = Category::find($id);

        return $app['twig']->render('category_edit.html.twig', array('category'=> $category));
    });

    $app->post("/tasks", function() use ($app) {

        $description = $_POST['description'];

        $due = $_POST['due'];
        $task = new Task($description, $id = null, $due);
        $task->save();
        // $category = Category::find($category_id);

        return $app['twig']->render('tasks.html.twig', array('category' => $task->getCategories(), 'tasks' => Task::getAll()));
    });

    $app->post("/categories", function() use ($app){
        $category = new Category($_POST['name']);
        $category->save();
        return $app['twig']->render('categories.html.twig', array('categories'=> Category::getAll()));
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

    $app->patch("/task/{id}", function($id) use ($app){
        $task = Task::find($id);
        $new_description = $_POST['name'];
        $new_due = $_POST['due'];
        $task->update($new_description, $new_due);
      return $app['twig']->render('task.html.twig', array('task'=>$task, 'all_categories'=>Category::getAll(), 'categories'=>$task->getCategories()));
    });

    $app->patch("/categories/{id}", function($id) use ($app){
        $category = Category::find($id);
        $new_name = $_POST['name'];
        $category->update($new_name);
      return $app['twig']->render('category.html.twig', array('category'=>$category, 'all_tasks'=>Task::getAll(), 'tasks'=>$category->getTasks()));
    });

    $app->delete("/categories/{id}", function($id) use ($app)
    {
        $category = Category::find($id);

        $category->delete();
        return $app['twig']->render('categories.html.twig', array('categories'=> Category::getAll()));
    });

    $app->delete("/task/{id}", function($id) use ($app)
    {
        $task = Task::find($id);

        $task->delete();
        return $app['twig']->render('tasks.html.twig', array('tasks'=> Task::getAll()));
    });


    return $app;
?>
