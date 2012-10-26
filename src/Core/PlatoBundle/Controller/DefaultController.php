<?php

namespace Core\PlatoBundle\Controller;

use CULabs\AdminBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/menu/{menu_slug}/plato/{plato_slug}", name="plato_show")
     */
    public function showAction($menu_slug, $plato_slug)
    {
        $datos = $this->getDataShow($plato_slug);
        
        $menu   = $this->getRepository('CorePlanBundle:Menu')->getBySlug($menu_slug);
        
        if (!$menu) {
            throw $this->createNotFoundException();
        }
        
        $datos['menu'] = $menu;
        
        $response = $this->render('CorePlatoBundle:Default:show.html.twig', $datos);
        $response->setSharedMaxAge($this->container->getParameter('cache_max_tiempo'));
        
        return $response;
    }
    
    /**
     * @Route("/plato/{slug}", name="plato_show_simple")
     */
    public function showSimpleAction($slug)
    {        
        $response = $this->render('CorePlatoBundle:Default:showSimple.html.twig', $this->getDataShow($slug));
        $response->setSharedMaxAge($this->container->getParameter('cache_max_tiempo'));
        
        return $response;
    }
    
    private function getDataShow($slug)
    {
        $entity = $this->getRepository('CorePlatoBundle:Plato')->getBySlug($slug);
        
        if (!$entity) {
            throw $this->createNotFoundException();
        }
        
        return array(
            'entity' => $entity,
        );
    }
    
    /**
     * @Template() 
     */
    public function destacadosAction()
    {
        return array(
            'destacados' => $this->getRepository('CorePlatoBundle:Plato')->findBy(array(
                'destacado' => true,
            )),
        );
    }
    
    /**
     * @Route("/platos.rss", name="rss_plato")
     */
    public function rssAction()
    {
        $fecha = new \DateTime();
        return $this->render('CorePlatoBundle:Default:platos.rss.twig', array(
            'platos' => $this->getRepository('CorePlatoBundle:Plato')->getPlatosByFechaPlan($fecha),
            'fecha'  => $fecha,
        ));
    }
}
