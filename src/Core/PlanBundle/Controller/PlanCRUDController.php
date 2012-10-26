<?php

namespace Core\PlanBundle\Controller;

use CULabs\AdminBundle\Controller\CRUDController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Core\PlanBundle\Entity\Plan;
use Core\PlanBundle\Form\PlanType;
use Core\PlanBundle\Filter\PlanFilterType;
use Core\PlanBundle\Entity\PlanMomento;
use Core\PlanBundle\Form\PlanMomentoType;

/**
 * Plan controller.
 *
 * @Route("/admin/plan")
 */
class PlanCRUDController extends CRUDController
{
    /**
     * Lists all Plan entities.
     *
     * @Route("", name="admin_plan")
     * @Template()
     * @Secure(roles="ROLE_PLAN_LIST")
     */
    public function indexAction()
    {        
        $page = $this->get('request')->query->get('page', $this->getPage());
        $this->setPage($page);
        $pager = $this->getPager();
        if ($this->get('request')->isXmlHttpRequest()) {
            return $this->render('CorePlanBundle:PlanCRUD:list.html.twig', array(
                'pager' => $pager,
                'sort'  => $this->getSort(),
            ));
        }
        $filter_form = $this->getFilterForm();
        return array(
            'pager'  => $pager,
            'filter' => $filter_form->createView(),
            'sort'   => $this->getSort(),
        );
    }
    
    /**
     * Filter Plan entities.
     *
     * @Route("/filter", name="admin_plan_filter")
     * @Method("post")     
     */
    public function filterAction()
    {        
        if ($this->getRequest()->request->get('action_reset')) {
            $this->setFilters(array());
            return $this->redirect($this->generateUrl('admin_plan'));
        }        
        $filter_form = $this->get('form.factory')->create(new PlanFilterType());        
        $filter_form->bindRequest($this->get('request'));        
        if ($filter_form->isValid()) {
            $this->setPage(1);
            $this->setFilters($filter_form->getData());
            return $this->redirect($this->generateUrl('admin_plan'));
        }        
        return $this->render('CorePlanBundle:PlanCRUD:index.html.twig', array(
            'filter' => $filter_form->createView(),
            'pager'  => $this->getPager(),
            'sort'   => $this->getSort(),
        ));
    }
    
    /**
     * Lista de compra.
     *
     * @Route("/{id}/lista-compra", name="admin_plan_list_compra")
     * @Template()
     * @Secure(roles="ROLE_PLAN_LIST_COMPRA")
     */
    public function listaCompraAction($id)
    {
        $entity = $this->getRepository('CorePlanBundle:Plan')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plan entity.');
        }
        
        return array(
            'reporte' => $this->get('plan.reporte')->generarListaCompra($entity),
            'plan'    => $entity,
        );
    }
    
    /**
     * Lista de compra render.
     * 
     * @Template()
     * @Secure(roles="ROLE_PLAN_LIST_COMPRA")
     */
    public function listaCompraRenderAction()
    {
        $fecha = new \DateTime();
        try {
            $plan = $this->getRepository('CorePlanBundle:Plan')
                        ->findPorFecha($fecha)
                        ->getQuery()
                        ->getSingleResult()
                    ;
        } catch (\Doctrine\ORM\NoResultException $e) {
            return array(
                'fecha' => $fecha,
            );
        }
        
        return array(
            'reporte' => $this->get('plan.reporte')->generarListaCompra($plan),
            'plan'    => $plan,
            'fecha' => $fecha,
        );
    }
    
    /**
     * Finds and displays a Plan entity.
     *
     * @Route("/{id}/show", name="admin_plan_show")
     * @Template()
     * @Secure(roles="ROLE_PLAN_SHOW")
     */
    public function showAction($id)
    {
        $entity = $this->getRepository('CorePlanBundle:Plan')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plan entity.');
        }
        
        $momentos = $this->getRepository('CorePlanBundle:Momento')->findAll();
        
        $menus = array();        
        foreach ($momentos as $item) {
            
            $return = $this->getRepository('CorePlanBundle:PlanMomento')
                           ->findByPlanAndMomento($entity, $item)
                           ->getQuery()
                           ->getOneOrNullResult()
                      ;
            if ($return) {
                
                $menus[] = $return;
            }
        }
        
        return array(
            'entity'   => $entity,
            'momentos' => $momentos,
            'menus'    => $menus,
        );
    }

    /**
     * Displays a form to create a new Plan entity.
     *
     * @Route("/new", name="admin_plan_new")
     * @Template()
     * @Secure(roles="ROLE_PLAN_NEW")
     */
    public function newAction()
    {
        $entity = new Plan();
        $form   = $this->createForm(new PlanType(), $entity);        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity Plan is saved.');
                return $this->redirect($this->generateUrl('admin_plan_show', array('id' => $entity->getId())));
            }
        }
        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Plan entity.
     *
     * @Route("/{id}/edit", name="admin_plan_edit")
     * @Template()
     * @Secure(roles="ROLE_PLAN_EDIT")
     */
    public function editAction($id)
    {
        $entity = $this->getRepository('CorePlanBundle:Plan')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plan entity.');
        }
        $form = $this->createForm(new PlanType(), $entity);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity Plan is saved.');
                return $this->redirect($this->generateUrl('admin_plan_show', array('id' => $entity->getId())));
            }
        } 
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes a Plan entity.
     *
     * @Route("/{id}/delete", name="admin_plan_delete")
     * @Secure(roles="ROLE_PLAN_DELETE")
     */
    public function deleteAction($id)
    {
        $entity = $this->getRepository('CorePlanBundle:Plan')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plan entity.');
        }
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
        $this->setFlash('notice', 'The entity Plan is deleted.');
        return $this->redirect($this->generateUrl('admin_plan'));
    }
    
    /**
     * Batch actions for Plan entity.
     *
     * @Route("/batch", name="admin_plan_batch")
     * @Secure(roles="ROLE_PLAN_DELETE")
     */
    public function batchAction()
    {
        $method = $this->getRequest()->request->get('batch_action');
        if (!$method) {
            $this->setFlash('error', 'Select a action');
            return $this->redirect($this->generateUrl('admin_plan'));
        }
        $method = $method.'Batch';
        
        if (!method_exists($this, $method)) {
            throw new \UnexpectedValueException('The bacth method not defined');
        }
        
        $ids = $this->getRequest()->request->get('ids');
        
        if (!count($ids)) {
            $this->setFlash('error', 'Select a record');
            return $this->redirect($this->generateUrl('admin_plan'));
        }
        
        $this->$method($ids);
        
        return $this->redirect($this->generateUrl('admin_plan'));
    }
        
    protected function deleteBatch($ids)
    {
        $qb = $this->getRepository('CorePlanBundle:Plan')->createQueryBuilder('Plan');
        $qb->delete()->where($qb->expr()->in('Plan.id', $ids));
        $qb->getQuery()->execute();
        
        $this->getRequest()->getSession()->setFlash('notice', 'The records are deleted.');
    }
        
    /**
     * Change Max Per Page.
     *
     * @Route("/changemaxperpage", name="admin_plan_changemaxperpage")
     * @Secure(roles="ROLE_PLAN_LIST")
     */
    public function changeMaxPerPageAction()
    {
        $this->setSession('maxperpage', $this->get('request')->query->get('cant'));
        $this->setPage(1);
        return $this->redirect($this->generateUrl('admin_plan'));
    }
    
    /**
     * Change Sort.
     *
     * @Route("/{field}/{order}/short", name="admin_plan_sort")
     * @Secure(roles="ROLE_PLAN_LIST")
     */
    public function sortAction($field, $order)
    {
        $this->setPage(1);
        $this->setSort(array(
            'field' => $field,
            'order' => $order,
            'next'  => $order == 'ASC'? 'DESC': 'ASC',
        ));
        return $this->redirect($this->generateUrl('admin_plan'));
    }
    
    /**
     * Add Plan Momento.
     *
     * @Route("/plan-momento/{id_plan}/{id_momento}/add", name="admin_add_plan_momento")
     * @Secure(roles="ROLE_PLAN_ADD_PLANMOMENTO")
     * @Template()
     */
    public function addPlanMomentoAction($id_plan, $id_momento)
    {
        $plan    = $this->getRepository('CorePlanBundle:Plan')->find($id_plan);
        $momento = $this->getRepository('CorePlanBundle:Momento')->find($id_momento);
        
        $entity = $this->getRepository('CorePlanBundle:PlanMomento')->findOneBy(array(
            'plan'    => $plan,
            'momento' => $momento
        ));
        
        if (!$entity) {
            
            $entity = new PlanMomento();
        }
        
        $entity->setMomento($momento);
        $entity->setPlan($plan);
        
        $form   = $this->createForm(new PlanMomentoType(), $entity);
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'El menú fue añadido.');
                return $this->redirect($this->generateUrl('admin_plan_show', array('id' => $plan->getId())));
            }
        }
        return array(
            'entity' => $entity,
            'plan'   => $plan,
            'form'   => $form->createView()
        );
    }
    
    /**
     * Eliminar Plan Momento.
     *
     * @Route("/plan-momento/{id_plan_momento}/{id_menu}/delete", name="admin_delete_plan_momento")
     * @Secure(roles="ROLE_PLAN_DELETE_PLANMOMENTO")
     * @Template()
     */
    public function deletePlanMomentoAction($id_plan_momento, $id_menu) 
    {
        $plan_momento = $this->getRepository('CorePlanBundle:PlanMomento')->find($id_plan_momento);
        $menu = $this->getRepository('CorePlanBundle:Menu')->find($id_menu);
        $plan_momento->removeMenu($menu);
        $em = $this->getEntityManager();
        $em->persist($plan_momento);
        $em->flush();
        $this->setFlash('notice', 'El menú fue eliminado.');
        return $this->redirect($this->generateUrl('admin_plan_show', array('id' => $plan_momento->getPlan()->getId())));
    }
    
    protected function getPager()
    {
        $filter_form = $this->getFilterForm();        
        $qb = $this->getRepository('CorePlanBundle:Plan')
                   ->createQueryBuilder('Plan')
        ;
        $sort = $this->getSort();
        if ($sort) {
            $qb->add('orderBy', sprintf('Plan.%s %s', $sort['field'], $sort['order']));
        }
        $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filter_form, $qb);
        $pager = $this->get('knp_paginator')->paginate(
            $qb->getQuery(),
            $this->getPage(),
            $this->getMaxPerPage()
        );        
        return $pager;
    }
    
    protected function getFilterForm()
    {
        $filter_form = $this->get('form.factory')->create(new PlanFilterType());        
        $filter_form->bind($this->getFilters());
        return $filter_form;
    }    
}
