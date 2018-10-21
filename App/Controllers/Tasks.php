<?php

namespace App\Controllers;
defined("CORE_PATH") OR die("Acceso denegado");

use App\Models\CategoryQuery;
use App\Models\Task;
use App\Models\TaskQuery;
use Core\Controller;
use Core\View;
use Propel\Runtime\Exception\PropelException;

/**
 * Controlador que maneja Tasks
 * @package App\Controllers
 */
class Tasks extends Controller
{
    /**
     * Tasks constructor.
     * Llama a controller y le pasa la extensión de la template
     */
    public function __construct()
    {
        parent::__construct("phtml");
    }

    /**
     * Método principal del controlador
     * @return void
     */
    public function index(): void
    {
        $tasks = TaskQuery::create()->find();
        $data = ["tasks" => $tasks];
        View::setTitlePage("Mira tus tareas");
        View::render("index", $data);
    }

    /**
     * Método Para agregar Tareas
     */
    public function add(): void
    {
        if ($_POST) {
            $task = new Task();
            try {
                $task->setId(null);
                $task->setName($_POST["name"]);
                $task->setDate($_POST["date"]);
                $task->setDescription($_POST["description"]);
                $task->setPriority($_POST["priority"]);
                $task->setCategoryId($_POST["category_id"]);
                $task->setStatus(1);
                $task->save();
                header("Location: " . APP_URL . "tasks");
                die();

            } catch (PropelException $e) {
                echo $e->getMessage();
            }
        }
        if ($_GET) {
            $categories = CategoryQuery::create()->find();
            $data = ["categories" => $categories];
            View::setTitlePage("Agrega una Tarea");
            View::render("add", $data);
        }
    }

    public function update(): void
    {
        $item = $this->getRequest()->getArgs()[0];
        $task = TaskQuery::create()->findPk($item);
        if ($_GET) {

            $categories = CategoryQuery::create()->find();
            $data = [
                "task" => $task,
                "categories" => $categories
            ];
            View::setTitlePage("Actualizar Tarea");
            View::render("update", $data);
        }
        if($_POST){
            try {
                $task->setName($_POST["name"]);
                $task->setDate($_POST["date"]);
                $task->setDescription($_POST["description"]);
                $task->setPriority($_POST["priority"]);
                $task->setCategoryId($_POST["category_id"]);
                $task->setStatus(1);
                $task->save();


            } catch (PropelException $e) {
                echo $e->getMessage();
            } finally{
                header("Location: " . APP_URL . "tasks");
                exit();
            }
        }
    }

    public function delete(): void {
        if ($_GET){
            try {
                $item = $this->getRequest()->getArgs()[0];
                $task = TaskQuery::create()->findPk($item);
                $task->delete();
                header("Location: " . APP_URL . "tasks");
                exit();
            } catch (PropelException $exception){
                $exception->getMessage();
            }
        }
    }
}