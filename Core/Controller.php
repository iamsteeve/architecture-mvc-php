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
     * Método principal de un controlador
     * @return void
     */
    abstract public function index(): void;
}