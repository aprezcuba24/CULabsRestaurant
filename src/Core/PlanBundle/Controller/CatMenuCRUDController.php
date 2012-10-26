<?php

namespace Core\PlanBundle\Controller;

use CULabs\AdminBundle\Controller\CRUDController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Core\PlanBundle\Entity\CatMenu;
use Core\PlanBundle\Form\CatMenuType;
use Core\PlanBundle\Filter\CatMenuFilterType;

/**
 * CatMenu controller.
 *
 * @Route("/admin/menu/categoria")
 */
class CatMenuCRUDController extends CRUDController
{
    /**
     * Lists all CatMenu entities.
     *
     * @Route("", name="admin_menu_categoria")
     * @Template()
     * @Secure(roles="ROLE_CATMENU_LIST")
     */
    public function indexAction()
    {        
        $page = $this->get('request')->query->get('page', $this->getPage());
        $this->setPage($page);
        $pager = $this->getPager();
        if ($this->get('request')->isXmlHttpRequest()) {
            return $this->render('CorePlanBundle:CatMenuCRUD:list.html.twig', array(
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
     * Filter CatMenu entities.
     *
     * @Route("/filter", name="admin_menu_categoria_filter")
     * @Method("post")     
     */
    public function filterAction()
    {        
        if ($this->getRequest()->request->get('action_reset')) {
            $this->setFilters(array());
            return $this->redirect($this->generateUrl('admin_menu_categoria'));
        }        
        $filter_form = $this->get('form.factory')->create(new CatMenuFilterType());        
        $filter_form->bindRequest($this->get('request'));        
        if ($filter_form->isValid()) {
            $this->setPage(1);
            $this->setFilters($filter_form->getData());
            return $this->redirect($this->generateUrl('admin_menu_categoria'));
        }        
        return $this->render('CorePlanBundle:CatMenu:index.html.twig', array(
            'filter' => $filter_form->createView(),
            'pager'  => $this->getPager(),
        ));
    }
    
    /**
     * Finds and displays a CatMenu entity.
     *
     * @Route("/{id}/show", name="admin_menu_categoria_show")
     * @Template()
     * @Secure(roles="ROLE_CATMENU_SHOW")
     */
    public function showAction($id)
    {
        $entity = $this->getRepository('CorePlanBundle:CatMenu')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CatMenu entity.');
        }
        return array(
            'entity' => $entity,
        );
    }

    /**
     * Displays a form to create a new CatMenu entity.
     *
     * @Route("/new", name="admin_menu_categoria_new")
     * @Template()
     * @Secure(roles="ROLE_CATMENU_NEW")
     */
    public function newAction()
    {
        $entity = new CatMenu();
        $form   = $this->createForm(new CatMenuType(), $entity);        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity CatMenu is saved.');
                return $this->redirect($this->generateUrl('admin_menu_categoria_show', array('id' => $entity->getId())));
            }
        }
        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing CatMenu entity.
     *
     * @Route("/{id}/edit", name="admin_menu_categoria_edit")
     * @Template()
     * @Secure(roles="ROLE_CATMENU_EDIT")
     */
    public function editAction($id)
    {
        $entity = $this->getRepository('CorePlanBundle:CatMenu')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CatMenu entity.');
        }
        $form = $this->createForm(new CatMenuType(), $entity);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity CatMenu is saved.');
                return $this->redirect($this->generateUrl('admin_menu_categoria_show', array('id' => $entity->getId())));
            }
        } 
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes a CatMenu entity.
     *
     * @Route("/{id}/delete", name="admin_menu_categoria_delete")
     * @Secure(roles="ROLE_CATMENU_DELETE")
     */
    public function deleteAction($id)
    {
        $entity = $this->getRepository('CorePlanBundle:CatMenu')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CatMenu entity.');
        }
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
        $this->setFlash('notice', 'The entity CatMenu is deleted.');
        return $this->redirect($this->generateUrl('admin_menu_categoria'));
    }
    
    /**
     * Batch actions for CatMenu entity.
     *
     * @Route("/batch", name="admin_menu_categoria_batch")
     * @Secure(roles="ROLE_CATMENU_DELETE")
     */
    public function batchAction()
    {
        $method = $this->getRequest()->request->get('batch_action');
        if (!$method) {
            $this->setFlash('error', 'Select a action');
            return $this->redirect($this->generateUrl('admin_menu_categoria'));
        }
        $method = $method.'Batch';
        
        if (!method_exists($this, $method)) {
            throw new \UnexpectedValueException('The bacth method not defined');
        }
        
        $ids = $this->getRequest()->request->get('ids');
        
        if (!count($ids)) {
            $this->setFlash('error', 'Select a record');
            return $this->redirect($this->generateUrl('admin_menu_categoria'));
        }
        
        $this->$method($ids);
        
        return $this->redirect($this->generateUrl('admin_menu_categoria'));
    }
        
    protected function deleteBatch($ids)
    {
        $qb = $this->getRepository('CorePlanBundle:CatMenu')->createQueryBuilder('CatMenu');
        $qb->delete()->where($qb->expr()->in('CatMenu.id', $ids));
        $qb->getQuery()->execute();
        
        $this->getRequest()->getSession()->setFlash('notice', 'The records are deleted.');
    }
        
    /**
     * Change Max Per Page.
     *
     * @Route("/changemaxperpage", name="admin_menu_categoria_changemaxperpage")
     * @Secure(roles="ROLE_CATMENU_LIST")
     */
    public function changeMaxPerPageAction()
    {
        $this->setSession('maxperpage', $this->get('request')->query->get('cant'));
        $this->setPage(1);
        return $this->redirect($this->generateUrl('admin_menu_categoria'));
    }
    
    /**
     * Change Sort.
     *
     * @Route("/{field}/{order}/short", name="admin_menu_categoria_sort")
     * @Secure(roles="ROLE_CATMENU_LIST")
     */
    public function sortAction($field, $order)
    {
        $this->setPage(1);
        $this->setSort(array(
            'field' => $field,
            'order' => $order,
            'next'  => $order == 'ASC'? 'DESC': 'ASC',
        ));
        return $this->redirect($this->generateUrl('admin_menu_categoria'));
    }
    
    protected function getPager()
    {
        $filter_form = $this->getFilterForm();        
        $qb = $this->getRepository('CorePlanBundle:CatMenu')
                   ->createQueryBuilder('CatMenu')
        ;
        $sort = $this->getSort();
        if ($sort) {
            $qb->add('orderBy', sprintf('CatMenu.%s %s', $sort['field'], $sort['order']));
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
        $filter_form = $this->get('form.factory')->create(new CatMenuFilterType());        
        $filter_form->bind($this->getFilters());
        return $filter_form;
    }    
}
