<?php

namespace Core\PlanBundle\Controller;

use CULabs\AdminBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("")
     */
    public function indexAction()
    {
        $hoy = new \DateTime();
        return $this->forward('CorePlanBundle:Default:show', array(
            'fecha' => $hoy->format('Y-m-d'),
        ));
    }
    
    /**
     * @Route("/ofertas/{fecha}", name="plan_show")
     */
    public function showAction($fecha)
    {
        $fecha = new \DateTime($fecha);
        
        try {
            $plan = $this->getRepository('CorePlanBundle:Plan')
                        ->findPorFecha($fecha)
                        ->getQuery()
                        ->getSingleResult()
                    ;
        } catch (\Doctrine\ORM\NoResultException $e) {
            return $this->render('CorePlanBundle:Default:planNoEncontrado.html.twig', array(
                'fecha' => $fecha,
            ));
        }        
        
        $response = $this->render('CorePlanBundle:Default:show.html.twig', array_merge(array(
            'plan'      => $plan,
        ), $this->procesarFecha($fecha)));
        
        $response->setSharedMaxAge($this->container->getParameter('cache_max_tiempo'));
        
        return $response;
    }
    protected function procesarFecha(\DateTime $fecha)
    {
        $anterior  = clone $fecha;
        $siguiente = clone $fecha;
        
        $data = array(
            'siguiente' => $siguiente->add(new \DateInterval('P0Y0M1DT0H0M0S')),
            'anterior'  => $anterior->sub(new \DateInterval('P0Y0M1DT0H0M0S')),
        );
        
        $que_dia = 'cualquier_dia';        
        foreach (array('hoy' => new \DateTime('today'), 'manana' => new \DateTime('today + 1 day'), 'pasado_manana' => new \DateTime('today + 2 day')) as $key => $item) {
            
            if ($fecha->diff($item, true)->days == 0) {
               $que_dia = $key;
               break;
            }
        }
        
        $data['que_dia'] = $que_dia;
        
        return $data;
    }

    /**
     * @Template() 
     */
    public function calendarAction()
    {
        return array();
    }
    
    /**
     * @Template() 
     */
    public function menuSuperiorAction()
    {
        return array(
            'hoy'           => new \DateTime('today'),
            'manana'        => new \DateTime('today + 1 day'),
            'pasado_manana' => new \DateTime('today + 2 day'),
        );
    }
}
