<?php

namespace App\Controllers;


use App\Models\Category;
use App\Models\CategoryQuery;
use Core\Controller;
use Core\View;
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
        $categories = CategoryQuery::create()->find();
        $data = [
            "categories" => $categories
        ];
        View::setTitlePage("Mira todas las categorias");
        View::render("index",$data);
    }

    public function add(): void
    {

        if ($_POST) {
            $category = new Category();
            try {
                $category->setId(null);
                $category->setName($_POST["name"]);
                $category->setDescription($_POST["description"]);
                $category->save();
                header("Location: " . APP_URL . "categories");
                exit();

            } catch (PropelException $e) {
                echo $e->getMessage();
            }
        }
        if ($_GET) {
            View::setTitlePage("Agrega una Categoría");
            View::render("add");
        }
    }

    public function update(): void
    {
        $item = $this->getRequest()->getArgs()[0];
        $category = CategoryQuery::create()->findPk($item);
        if ($_GET) {

            $data = [
                "category" => $category
            ];
            View::setTitlePage("Actualizar Categoría");
            View::render("update", $data);
        }
        if($_POST){
            try {
                $category->setName($_POST["name"]);
                $category->setDescription($_POST["description"]);
                $category->save();
                header("Location: " . APP_URL . "categories");
                exit();

            } catch (PropelException $e) {
                echo $e->getMessage();
            }
        }
    }
    public function delete(): void
    {
        try{
            $item = $this->getRequest()->getArgs()[0];
            $category = CategoryQuery::create()->findPk($item);
            $category->delete();
            header("Location: " . APP_URL . "categories");
            exit();
        } catch (PropelException $exception)
        {
            echo $exception->getMessage();
        }


    }
}