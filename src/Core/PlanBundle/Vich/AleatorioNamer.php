<?php

namespace Core\PlanBundle\Vich;

use Vich\UploaderBundle\Naming\NamerInterface;
use Doctrine\Common\Util\Inflector;

class AleatorioNamer implements NamerInterface
{
    public function name($obj, $field)
    {
        $method = 'get'.ucfirst(Inflector::camelize($field));
        
        $file = $obj->$method();
        
        return uniqid('a').'.'.$file->guessExtension();
    }
}