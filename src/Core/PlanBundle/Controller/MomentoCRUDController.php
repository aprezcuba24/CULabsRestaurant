<?php

namespace Core\PlanBundle\Controller;

use CULabs\AdminBundle\Controller\CRUDController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Core\PlanBundle\Entity\Momento;
use Core\PlanBundle\Form\MomentoType;
use Core\PlanBundle\Filter\MomentoFilterType;

/**
 * Momento controller.
 *
 * @Route("/admin/momento")
 */
class MomentoCRUDController extends CRUDController
{
    /**
     * Lists all Momento entities.
     *
     * @Route("", name="admin_momento")
     * @Template()
     * @Secure(roles="ROLE_MOMENTO_LIST")
     */
    public function indexAction()
    {        
        $page = $this->get('request')->query->get('page', $this->getPage());
        $this->setPage($page);
        $pager = $this->getPager();
        if ($this->get('request')->isXmlHttpRequest()) {
            return $this->render('CorePlanBundle:MomentoCRUD:list.html.twig', array(
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
     * Filter Momento entities.
     *
     * @Route("/filter", name="admin_momento_filter")
     * @Method("post")     
     */
    public function filterAction()
    {        
        if ($this->getRequest()->request->get('action_reset')) {
            $this->setFilters(array());
            return $this->redirect($this->generateUrl('admin_momento'));
        }        
        $filter_form = $this->get('form.factory')->create(new MomentoFilterType());        
        $filter_form->bindRequest($this->get('request'));        
        if ($filter_form->isValid()) {
            $this->setPage(1);
            $this->setFilters($filter_form->getData());
            return $this->redirect($this->generateUrl('admin_momento'));
        }        
        return $this->render('CorePlanBundle:Momento:index.html.twig', array(
            'filter' => $filter_form->createView(),
            'pager'  => $this->getPager(),
        ));
    }
    
    /**
     * Finds and displays a Momento entity.
     *
     * @Route("/{id}/show", name="admin_momento_show")
     * @Template()
     * @Secure(roles="ROLE_MOMENTO_SHOW")
     */
    public function showAction($id)
    {
        $entity = $this->getRepository('CorePlanBundle:Momento')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Momento entity.');
        }
        return array(
            'entity' => $entity,
        );
    }

    /**
     * Displays a form to create a new Momento entity.
     *
     * @Route("/new", name="admin_momento_new")
     * @Template()
     * @Secure(roles="ROLE_MOMENTO_NEW")
     */
    public function newAction()
    {
        $entity = new Momento();
        $form   = $this->createForm(new MomentoType(), $entity);        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity Momento is saved.');
                return $this->redirect($this->generateUrl('admin_momento_show', array('id' => $entity->getId())));
            }
        }
        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Momento entity.
     *
     * @Route("/{id}/edit", name="admin_momento_edit")
     * @Template()
     * @Secure(roles="ROLE_MOMENTO_EDIT")
     */
    public function editAction($id)
    {
        $entity = $this->getRepository('CorePlanBundle:Momento')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Momento entity.');
        }
        $form = $this->createForm(new MomentoType(), $entity);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity Momento is saved.');
                return $this->redirect($this->generateUrl('admin_momento_show', array('id' => $entity->getId())));
            }
        } 
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes a Momento entity.
     *
     * @Route("/{id}/delete", name="admin_momento_delete")
     * @Secure(roles="ROLE_MOMENTO_DELETE")
     */
    public function deleteAction($id)
    {
        $entity = $this->getRepository('CorePlanBundle:Momento')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Momento entity.');
        }
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
        $this->setFlash('notice', 'The entity Momento is deleted.');
        return $this->redirect($this->generateUrl('admin_momento'));
    }
    
    /**
     * Batch actions for Momento entity.
     *
     * @Route("/batch", name="admin_momento_batch")
     * @Secure(roles="ROLE_MOMENTO_DELETE")
     */
    public function batchAction()
    {
        $method = $this->getRequest()->request->get('batch_action');
        if (!$method) {
            $this->setFlash('error', 'Select a action');
            return $this->redirect($this->generateUrl('admin_momento'));
        }
        $method = $method.'Batch';
        
        if (!method_exists($this, $method)) {
            throw new \UnexpectedValueException('The bacth method not defined');
        }
        
        $ids = $this->getRequest()->request->get('ids');
        
        if (!count($ids)) {
            $this->setFlash('error', 'Select a record');
            return $this->redirect($this->generateUrl('admin_momento'));
        }
        
        $this->$method($ids);
        
        return $this->redirect($this->generateUrl('admin_momento'));
    }
        
    protected function deleteBatch($ids)
    {
        $qb = $this->getRepository('CorePlanBundle:Momento')->createQueryBuilder('Momento');
        $qb->delete()->where($qb->expr()->in('Momento.id', $ids));
        $qb->getQuery()->execute();
        
        $this->getRequest()->getSession()->setFlash('notice', 'The records are deleted.');
    }
        
    /**
     * Change Max Per Page.
     *
     * @Route("/changemaxperpage", name="admin_momento_changemaxperpage")
     * @Secure(roles="ROLE_MOMENTO_LIST")
     */
    public function changeMaxPerPageAction()
    {
        $this->setSession('maxperpage', $this->get('request')->query->get('cant'));
        $this->setPage(1);
        return $this->redirect($this->generateUrl('admin_momento'));
    }
    
    /**
     * Change Sort.
     *
     * @Route("/{field}/{order}/short", name="admin_momento_sort")
     * @Secure(roles="ROLE_MOMENTO_LIST")
     */
    public function sortAction($field, $order)
    {
        $this->setPage(1);
        $this->setSort(array(
            'field' => $field,
            'order' => $order,
            'next'  => $order == 'ASC'? 'DESC': 'ASC',
        ));
        return $this->redirect($this->generateUrl('admin_momento'));
    }
    
    protected function getPager()
    {
        $filter_form = $this->getFilterForm();        
        $qb = $this->getRepository('CorePlanBundle:Momento')
                   ->createQueryBuilder('Momento')
        ;
        $sort = $this->getSort();
        if ($sort) {
            $qb->add('orderBy', sprintf('Momento.%s %s', $sort['field'], $sort['order']));
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
        $filter_form = $this->get('form.factory')->create(new MomentoFilterType());        
        $filter_form->bind($this->getFilters());
        return $filter_form;
    }    
}
