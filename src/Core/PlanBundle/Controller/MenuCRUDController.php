<?php

namespace Core\PlanBundle\Controller;

use CULabs\AdminBundle\Controller\CRUDController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Core\PlanBundle\Entity\Menu;
use Core\PlanBundle\Form\MenuType;
use Core\PlanBundle\Filter\MenuFilterType;

/**
 * Menu controller.
 *
 * @Route("/admin/menu")
 */
class MenuCRUDController extends CRUDController
{
    /**
     * Lists all Menu entities.
     *
     * @Route("", name="admin_menu")
     * @Template()
     * @Secure(roles="ROLE_MENU_LIST")
     */
    public function indexAction()
    {        
        $page = $this->get('request')->query->get('page', $this->getPage());
        $this->setPage($page);
        $pager = $this->getPager();
        if ($this->get('request')->isXmlHttpRequest()) {
            return $this->render('CorePlanBundle:MenuCRUD:list.html.twig', array(
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
     * Filter Menu entities.
     *
     * @Route("/filter", name="admin_menu_filter")
     * @Method("post")     
     */
    public function filterAction()
    {        
        if ($this->getRequest()->request->get('action_reset')) {
            $this->setFilters(array());
            return $this->redirect($this->generateUrl('admin_menu'));
        }        
        $filter_form = $this->get('form.factory')->create(new MenuFilterType());        
        $filter_form->bindRequest($this->get('request'));        
        if ($filter_form->isValid()) {
            $this->setPage(1);
            $this->setFilters($filter_form->getData());
            return $this->redirect($this->generateUrl('admin_menu'));
        }        
        return $this->render('CorePlanBundle:Menu:index.html.twig', array(
            'filter' => $filter_form->createView(),
            'pager'  => $this->getPager(),
        ));
    }
    
    /**
     * Finds and displays a Menu entity.
     *
     * @Route("/{id}/show", name="admin_menu_show")
     * @Template()
     * @Secure(roles="ROLE_MENU_SHOW")
     */
    public function showAction($id)
    {
        $entity = $this->getRepository('CorePlanBundle:Menu')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Menu entity.');
        }
        return array(
            'entity' => $entity,
        );
    }

    /**
     * Displays a form to create a new Menu entity.
     *
     * @Route("/new", name="admin_menu_new")
     * @Template()
     * @Secure(roles="ROLE_MENU_NEW")
     */
    public function newAction()
    {
        $entity = new Menu();
        $form   = $this->createForm(new MenuType(), $entity);        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity Menu is saved.');
                return $this->redirect($this->generateUrl('admin_menu_show', array('id' => $entity->getId())));
            }
        }
        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Menu entity.
     *
     * @Route("/{id}/edit", name="admin_menu_edit")
     * @Template()
     * @Secure(roles="ROLE_MENU_EDIT")
     */
    public function editAction($id)
    {
        $entity = $this->getRepository('CorePlanBundle:Menu')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Menu entity.');
        }
        $form = $this->createForm(new MenuType(), $entity, array(
            'foto_reqerida' => false,
        ));
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity Menu is saved.');
                return $this->redirect($this->generateUrl('admin_menu_show', array('id' => $entity->getId())));
            }
        } 
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes a Menu entity.
     *
     * @Route("/{id}/delete", name="admin_menu_delete")
     * @Secure(roles="ROLE_MENU_DELETE")
     */
    public function deleteAction($id)
    {
        $entity = $this->getRepository('CorePlanBundle:Menu')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Menu entity.');
        }
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
        $this->setFlash('notice', 'The entity Menu is deleted.');
        return $this->redirect($this->generateUrl('admin_menu'));
    }
    
    /**
     * Batch actions for Menu entity.
     *
     * @Route("/batch", name="admin_menu_batch")
     * @Secure(roles="ROLE_MENU_DELETE")
     */
    public function batchAction()
    {
        $method = $this->getRequest()->request->get('batch_action');
        if (!$method) {
            $this->setFlash('error', 'Select a action');
            return $this->redirect($this->generateUrl('admin_menu'));
        }
        $method = $method.'Batch';
        
        if (!method_exists($this, $method)) {
            throw new \UnexpectedValueException('The bacth method not defined');
        }
        
        $ids = $this->getRequest()->request->get('ids');
        
        if (!count($ids)) {
            $this->setFlash('error', 'Select a record');
            return $this->redirect($this->generateUrl('admin_menu'));
        }
        
        $this->$method($ids);
        
        return $this->redirect($this->generateUrl('admin_menu'));
    }
        
    protected function deleteBatch($ids)
    {
        $qb = $this->getRepository('CorePlanBundle:Menu')->createQueryBuilder('Menu');
        $qb->delete()->where($qb->expr()->in('Menu.id', $ids));
        $qb->getQuery()->execute();
        
        $this->getRequest()->getSession()->setFlash('notice', 'The records are deleted.');
    }
        
    /**
     * Change Max Per Page.
     *
     * @Route("/changemaxperpage", name="admin_menu_changemaxperpage")
     * @Secure(roles="ROLE_MENU_LIST")
     */
    public function changeMaxPerPageAction()
    {
        $this->setSession('maxperpage', $this->get('request')->query->get('cant'));
        $this->setPage(1);
        return $this->redirect($this->generateUrl('admin_menu'));
    }
    
    /**
     * Change Sort.
     *
     * @Route("/{field}/{order}/short", name="admin_menu_sort")
     * @Secure(roles="ROLE_MENU_LIST")
     */
    public function sortAction($field, $order)
    {
        $this->setPage(1);
        $this->setSort(array(
            'field' => $field,
            'order' => $order,
            'next'  => $order == 'ASC'? 'DESC': 'ASC',
        ));
        return $this->redirect($this->generateUrl('admin_menu'));
    }
    
    protected function getPager()
    {
        $filter_form = $this->getFilterForm();        
        $qb = $this->getRepository('CorePlanBundle:Menu')
                   ->createQueryBuilder('Menu')
        ;
        $sort = $this->getSort();
        if ($sort) {
            $qb->add('orderBy', sprintf('Menu.%s %s', $sort['field'], $sort['order']));
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
        $filter_form = $this->get('form.factory')->create(new MenuFilterType());        
        $filter_form->bind($this->getFilters());
        return $filter_form;
    }    
}
