<?php

namespace App\Extensions;


use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class Tasks implements ExtensionInterface
{
    protected $engine;

    public function register(Engine $engine)
    {
        $this->engine = $engine;

        $engine->registerFunction('tasks', [$this, 'getObject']);


    }
    public function getObject()
    {
        return $this;
    }

    public function status($status)
    {
        return $status? 'Por Hacer': 'Completado!';
    }

    public function category($categoriesList, int $categoryOfTask)
    {
        foreach ($categoriesList as $category){
            if ($category->getId() === $categoryOfTask){
                return $category->getName();
            }
        }
    }
    public function formatDate(string $date): string {
        $date = explode('-', $date);
        $strinDate = '';
        $it=0;
        foreach ($date as $number){
            if ($it === 0){
                $strinDate .= $number. ' de ';
                $it= $it+1;
            }
            else if ($it===1){
                $strinDate .= $number. ' del ';
                $it= $it+1;
            }
            else if ($it===2){
                $strinDate .= $number;
                $it = $it+1;
            }
        }
        return $strinDate;
    }

    public function formatPriority(string $priority): string {
        switch ($priority){
            case 0:
                return '<span class="badge black white-text">Sin importancia</span>';
            case ($priority<=3):
                return '<span class="badge green white-text">Baja</span>';
            case ($priority<=6):
                return '<span class="badge orange white-text">Media</span>';
            case ($priority<=10):
                return '<span class="badge red white-text">Alta</span>';
            default:
                return 'None';
        }
    }

}