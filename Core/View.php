<?php

namespace Core;
defined("CORE_PATH") OR die("Acceso denegado");
use League\Plates\Engine;



/**
 * Se encarga de Procesar las vistas de la aplicación
 * @package Core
 */
class View
{


    /**
     * Controlador recibido por la URL
     * @var string
     */
    private static $_controller;
    /**
     * Extensión de las plantillas
     * @var string
     */
    private static $_extensionTemplates;
    /**
     * Instancia del motor de plantillas
     * @var Engine
     */
    private static $_templates;

    /**
     * Titulo de la página
     * @var string
     */
    private static $_titlePage = "";

    /**
     * Método para crear La vista
     * @param Request $request
     * @param string $extensionTemplates
     * @return void
     */
    public static function createView(Request $request, string $extensionTemplates = "phtml"): void{

        self::$_controller = $request->getController();
        self::$_extensionTemplates = $extensionTemplates;

        $layoutPath = VIEWS_FOLDER. "layouts".DS.DEFAULT_LAYOUT;
        $controllerPath = VIEWS_FOLDER. self::$_controller;

        self::$_templates = new Engine(VIEWS_FOLDER, self::$_extensionTemplates);
        self::$_templates->addFolder("layouts", $layoutPath);
        self::$_templates->addFolder(self::$_controller, $controllerPath);

    }


    /**
     * Método para enviar el titulo de una página a renderizar
     * @param string $titlePage
     */
    public static function setTitlePage(string $titlePage): void
    {
        self::$_titlePage = $titlePage;
    }


    /**
     * Método que renderiza la Template
     * @param $view
     * @param array $data
     */
    public static function render($view, $data = array()): void {
        $renderString = self::$_controller."::".$view;
        //TODO: Hay que retirar el render de layouot de aqui!!!!
        echo self::$_templates->render(
            "layouts::header",
            ["title"=> self::$_titlePage]
        );
        echo self::$_templates->render(
            $renderString,
            $data
        );
        echo self::$_templates->render(
            "layouts::footer"
        );
    }








}