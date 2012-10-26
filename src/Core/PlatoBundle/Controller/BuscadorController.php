<?php

namespace Core\PlatoBundle\Controller;

use CULabs\AdminBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BuscadorController extends Controller
{
    /**
     * @Route("/buscar", name="buscador")
     */
    public function buscadorAction()
    {
        $query = $this->getRequest()->get('q');
        $page  = $this->getRequest()->get('page', 1);
        
        $qb = $this->getRepository('CorePlatoBundle:Plato')->queryByName($query);
        
        $pager = $this->get('knp_paginator')->paginate(
            $qb->getQuery(),
            $page,
            4
        );
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            return $this->render('CorePlatoBundle:Buscador:list.html.twig', array(
                'pager' => $pager,
                'query' => $query,
            ));
        }
        
        return $this->render('CorePlatoBundle:Buscador:index.html.twig', array(
            'pager' => $pager,
            'query' => $query,
        ));
    }
}