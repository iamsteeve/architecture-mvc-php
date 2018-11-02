<?php

namespace App\Controllers;
defined("CORE_PATH") OR die("Acceso denegado");

use App\Models\CategoryQuery;
use App\Models\Task;
use App\Models\TaskQuery;
use Core\Controller;
use Core\View;
use Propel\Runtime\Exception\PropelException;
use Josantonius\Session\Session;


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
        try {
            $categories = CategoryQuery::create()->find();
            // $tasks = TaskQuery::create()->find();
            $tasks = TaskQuery::create()
                ->filterByCategory($categories)
                ->find();

            View::sendSessionToView();

            View::setData("tasks", $tasks);
            View::setData("title", "Mira tus tareas");
            View::render("index");
        } catch (PropelException $propelException) {
            echo $propelException->getMessage();
        }
    }

    /**
     * Método Para agregar Tareas
     */
    public function add(): void
    {

        try {
            if ($_POST) {
                $task = new Task();

                $task->setId(null);
                $task->setName($_POST["name"]);
                $task->setDate($_POST["date"]);
                $task->setDescription($_POST["description"]);
                $task->setPriority($_POST["priority"]);
                $task->setCategoryId($_POST["category_id"]);
                $task->setStatus(true);
                $task->save();
                Session::set("action","Tarea creada");
                $this->redirect(array("controller" => "tasks"));
                die();

            }
            if ($_GET) {
                $categories = CategoryQuery::create()->find();
                View::sendSessionToView();

                View::setData("title", "Agrega una Tarea");
                View::setData("categories", $categories);
                View::render("add");
            }
        } catch (PropelException $propelException){
            echo $propelException->getMessage();
        }
    }

    public function update(): void
    {
        try {
            $item = $this->getRequest()->getArgs()[0];
            $task = TaskQuery::create()->findPk($item);
            if ($_GET) {
                View::sendSessionToView();
                $categories = CategoryQuery::create()->find();
                View::setData("title", "Actualizar Tarea");
                View::setData("categories", $categories);
                View::setData("task", $task);
                View::render("update");
            }
            if($_POST){
                $task->setName($_POST["name"]);
                $task->setDate($_POST["date"]);
                $task->setDescription($_POST["description"]);
                $task->setPriority($_POST["priority"]);
                $task->setCategoryId($_POST["category_id"]);
                $task->save();
                Session::set("action", "Tarea Actualizada");
                $this->redirect(array("controller" => "tasks"));
                exit();
            }
        } catch (PropelException $propelException){
            echo $propelException->getMessage();
        }

    }

    public function delete($id): void {
        if ($_GET){
            try {
                $item = $this->getRequest()->getArgs()[0];
                $task = TaskQuery::create()->findPk($item);
                $task->delete();
                Session::set("action","Tarea Eliminada");
                $this->redirect(array("controller" => "tasks"));
                exit();
            } catch (PropelException $exception){
                $exception->getMessage();
            }
        }
    }

    public function status($id): void {
        if ($_GET){
            try {
                $task = TaskQuery::create()->findPk($id);
                if ((int) $task->getStatus()===1){
                    $task->setStatus(false);
                } else {
                    $task->setStatus(true);
                }

                $task->save();
                Session::set("action", "Status Actualizado");
                $this->redirect(array("controller" => "tasks"));
                exit();
            } catch (PropelException $exception){
                $exception->getMessage();
            }
        }


    }
}