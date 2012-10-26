<?php

namespace Core\PlanBundle\Model;

use Doctrine\ORM\EntityManager;
use Core\PlanBundle\Entity\Plan;

class Reporte
{
    private $em;
    
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function generarListaCompra(Plan $plan)
    {
        $datos = $this->em->getRepository('CorePlatoBundle:PlatoIngrediente')
                      ->findByPlanQuery($plan)
                      ->getQuery()
                      ->getResult()
                 ;
        
        $result = array();
        
        foreach ($datos as $item) {
            
            $id = $item->getIngrediente()->getId();
            
            if (!isset($result[$id])) {
                
                $result[$id]['ingrediente'] = $item->getIngrediente();
                $result[$id]['cantidad']    = 0;
            }
            
            $result[$id]['cantidad'] += $item->getCantidad() * $item->getIngrediente()->getPesoUnitario();
        }
        
        foreach ($result as $key => $item) {
            
            $result[$key]['cantidad'] *= $plan->getCantidadRaciones();
        }
        
        return $result;
    }
}