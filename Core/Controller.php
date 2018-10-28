<?php

namespace Core;
defined("CORE_PATH") OR die("Acceso denegado");

/**
 * Esta clase es una abstracción de los controladores y debe ser heredado por los controladores
 * @package Core
 */
abstract class Controller
{
    /**
     * Propiedad de la Request
     * @var Request
     */
    private $request;

    /**
     * Controller constructor.
     * El constructor crea un nuevo Request y Crea una vista al que se le puede pasar de forma opcional la extensión de la template
     * @param string $extensionTemplate
     */
    public function __construct($extensionTemplate = "phtml")
    {
        $this->request = new Request();
        View::createView($this->request, $extensionTemplate);
    }

    /**
     * Obtiene una instancia de la Request
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Método de redirección
     * @param array $url
     */
    public function redirect($url = array()): void {
        $path = "";
        if ($url["controller"]){
            $path .= $url["controller"];
        }
        if (isset($url["action"])) {
            $path.= "/".$url["action"];
        }

        header("Location: ". APP_URL.$path);

    }

    /**
     * Método principal de un controlador
     * @return void
     */
    abstract public function index(): void;
}