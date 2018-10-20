<?php

namespace App\Controllers;
defined("CORE_PATH") OR die("Acceso denegado");

use App\Models\TaskQuery;
use Core\Controller;
use Core\View;

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
}