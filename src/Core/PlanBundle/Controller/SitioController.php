<?php

namespace Core\PlanBundle\Controller;

use CULabs\AdminBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SitioController extends Controller
{
    /**
     * @Route("/sitio/{page}", name="sitio_page")
     */
    public function indexAction($page)
    {
        if (!in_array($page, array('politica', 'jurado'))) {
            throw $this->createNotFoundException();
        }
        return $this->render(sprintf('CorePlanBundle:Sitio:%s.html.twig', $page));
    }
}
