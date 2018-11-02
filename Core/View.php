<?php

namespace Core;
defined("CORE_PATH") OR die("Acceso denegado");

use App\Extensions\Tasks;
use Josantonius\Session\Session;
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
     * Instancia del motor de plantillas
     * @var Engine
     */
    private static $_templates;

    /**
     * Titulo de la página (Opcional)
     * @var string
     */
    private static $_titlePage;

    /**
     * Datos que serán pasados a la vista
     * @var array
     */
    private static $_data = array();

    /**
     * Método para crear La vista carga solo las carpetas necesarias para trabajar esto reduce carga
     * @param Request $request
     * @param string $extensionTemplates
     * @return void
     */
    public static function createView(Request $request, string $extensionTemplates = "phtml"): void {

        self::$_controller = $request->getController();

        $layoutPath = VIEWS_FOLDER. "layouts".DS.DEFAULT_LAYOUT;
        $controllerPath = VIEWS_FOLDER. self::$_controller;

        self::$_templates = new Engine(VIEWS_FOLDER, $extensionTemplates);
        self::$_templates->addFolder("layouts", $layoutPath);
        self::$_templates->addFolder(self::$_controller, $controllerPath);
        self::$_templates->loadExtension(new Tasks());
    }


    /**
     * Método para enviar el titulo de una página a renderizar
     * Puede usar este método o desde el mismo setData
     * @deprecated Este método ya no es necesario y agrega lógica al Core pero aun puede usarse
     * @param string $titlePage
     */
    public static function setTitlePage(string $titlePage): void
    {
        self::$_titlePage = $titlePage;
    }

    public static function sendSessionToView(): void {
        if (Session::get('action')){
            self::$_templates->addData(['action'=>Session::get('action')],'layouts::base');
            Session::set('action', false);
        }
    }

    /**
     * Pasa información importante a la vista según lo que le envíe el controlador
     * y le pasa un array con un name que será el nombre de la variable y el value
     * que será los datos de la variable
     * @param string $name
     * @param mixed $value
     */
    public static function setData($name, $value): void
    {
        self::$_data[$name] = $value;
    }


    /**
     * Método que renderiza la Template
     * @param $view
     */
    public static function render($view): void
    {
        self::verifyExtraData();
        $renderString = self::$_controller . "::" . $view;
        ob_start();
        echo self::$_templates->render(
            $renderString,
            self::$_data
        );

    }

    /**
     * Método que procesa data extra a las plantillas si es que se agregó
     * @return void
     */
    private static function verifyExtraData(): void
    {
        if (self::$_titlePage){
            self::setData('title',self::$_titlePage);
        }

    }

    /**
     * @return Engine
     */
    public static function getTemplates(): Engine
    {
        return self::$_templates;
    }



}