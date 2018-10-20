<?php
/**
 * Permite agregar separador de directorios según el sistema
 */
define("DS", DIRECTORY_SEPARATOR);

/**
 * Carperta Principal de la aplicación
 */
define("ROOT", realpath(dirname(__FILE__)) . DS);

/**
 * Carpeta Core de la aplicación
 */
define("CORE_PATH", ROOT . "Core" . DS);
/**
 * Archivo de Configuraciones
 */
require_once(CORE_PATH . "Config.php" );

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/generated-conf/config.php';

try {
    \Core\App::run(new \Core\Request());
} catch (Exception $e) {
    return $e->getMessage();
}
