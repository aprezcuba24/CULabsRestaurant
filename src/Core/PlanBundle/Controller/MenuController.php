<?php

namespace Core\PlanBundle\Controller;

use CULabs\AdminBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MenuController extends Controller
{
    /**
     * @Route("/menu/{slug}", name="menu_show")
     */
    public function showAction($slug)
    {
        $entity = $this->getRepository('CorePlanBundle:Menu')->getBySlug($slug);
        
        if (!$entity) {
            throw $this->createNotFoundException();
        }
        
        $response = $this->render('CorePlanBundle:Menu:show.html.twig', array(
            'entity' => $entity,
        ));
        $response->setSharedMaxAge($this->container->getParameter('cache_max_tiempo'));
        
        return $response;
    }
}