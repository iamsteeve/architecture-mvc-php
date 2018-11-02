<?php

namespace App\Controllers;


use App\Models\Category;
use App\Models\CategoryQuery;
use Core\Controller;
use Core\View;
use Josantonius\Session\Session;
use Propel\Runtime\Exception\PropelException;

class Categories extends Controller
{
    public function __construct()
    {
        parent::__construct("phtml");
    }

    /**
     * Método principal de un controlador
     * @return void
     */
    public function index(): void
    {
        try {
            $categories = CategoryQuery::create()->find();
            View::sendSessionToView();
            View::setData("title", "Mira todas las Categorías");
            View::setData("categories", $categories);
            View::render("index");

        } catch (PropelException $propelException) {
            echo $propelException->getMessage();
        }

    }

    public function add(): void
    {

        if ($_POST) {

            try {
                $category = new Category();
                $category->setId(null);
                $category->setName($_POST["name"]);
                $category->setDescription($_POST["description"]);
                $category->save();
                Session::set('action','Categoría agregada');
                $this->redirect(array("controller" => "categories"));
                exit();

            } catch (PropelException $e) {
                echo $e->getMessage();
            }
        }
        if ($_GET) {
            View::sendSessionToView();
            View::setData("title", "Agrega una Categoría");
            View::render("add");
        }
    }

    public function update(): void
    {
        try {
            $item = $this->getRequest()->getArgs()[0];
            $category = CategoryQuery::create()->findPk($item);
            if ($_GET) {
                View::sendSessionToView();
                View::setData("title", "Actualizar categoría");
                View::setData("category",$category);
                View::render("update");
            }
            if ($_POST) {

                $category->setName($_POST["name"]);
                $category->setDescription($_POST["description"]);
                $category->save();
                Session::set('action', 'Categoría Actualizada');
                $this->redirect(array("controller" => "categories"));
                exit();
            }
        } catch (PropelException $propelException) {
            echo $propelException->getMessage();
        }

    }

    public function delete(): void
    {
        try {
            $item = $this->getRequest()->getArgs()[0];
            $category = CategoryQuery::create()->findPk($item);
            $category->delete();
            Session::set('action', 'Categoría eliminada');
            $this->redirect(array("controller" => "categories"));
            exit();
        } catch (PropelException $exception) {
            echo $exception->getMessage();
        }


    }
}