<?php

namespace Fk\ORM;

use Fk\ORM\TableManager;
use Fk\ORM\Shema\Type\TypeManager;

class EntityManager {
    
    protected $entities = [];
    protected $tableManager;

    public function __construct(TableManager $tableManager) {
        $this->tableManager = $tableManager;
    }

    public function get($entityName) {
        if(!array_key_exists($entityName, $this->entities)) {
            $namespace = 'App\Model\Entity\\';
            $class = $namespace.ucfirst($entityName);
            if(!class_exists($class)) {
                $class = 'Fk\ORM\Entity';
            }
            $this->entities[$entityName] = new $class($entityName, $this->tableManager);
        }
        return $this->entities[$entityName];
    }

    public function set($entityName, $entity) {
        $this->entities[$entityName] = $entity;
    }

}