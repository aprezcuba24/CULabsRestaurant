<?php

namespace Core\PlatoBundle\Controller;

use CULabs\AdminBundle\Controller\CRUDController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Core\PlatoBundle\Entity\Ingrediente;
use Core\PlatoBundle\Form\IngredienteType;
use Core\PlatoBundle\Filter\IngredienteFilterType;

/**
 * Ingrediente controller.
 *
 * @Route("/admin/ingrediente")
 */
class IngredienteCRUDController extends CRUDController
{
    /**
     * Lists all Ingrediente entities.
     *
     * @Route("", name="admin_ingrediente")
     * @Template()
     * @Secure(roles="ROLE_INGREDIENTE_LIST")
     */
    public function indexAction()
    {        
        $page = $this->get('request')->query->get('page', $this->getPage());
        $this->setPage($page);
        $pager = $this->getPager();
        if ($this->get('request')->isXmlHttpRequest()) {
            return $this->render('CorePlatoBundle:IngredienteCRUD:list.html.twig', array(
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
     * Filter Ingrediente entities.
     *
     * @Route("/filter", name="admin_ingrediente_filter")
     * @Method("post")     
     */
    public function filterAction()
    {        
        if ($this->getRequest()->request->get('action_reset')) {
            $this->setFilters(array());
            return $this->redirect($this->generateUrl('admin_ingrediente'));
        }        
        $filter_form = $this->get('form.factory')->create(new IngredienteFilterType());        
        $filter_form->bindRequest($this->get('request'));        
        if ($filter_form->isValid()) {
            $this->setPage(1);
            $this->setFilters($filter_form->getData());
            return $this->redirect($this->generateUrl('admin_ingrediente'));
        }        
        return $this->render('CorePlatoBundle:Ingrediente:index.html.twig', array(
            'filter' => $filter_form->createView(),
            'pager'  => $this->getPager(),
        ));
    }
    
    /**
     * Finds and displays a Ingrediente entity.
     *
     * @Route("/{id}/show", name="admin_ingrediente_show")
     * @Template()
     * @Secure(roles="ROLE_INGREDIENTE_SHOW")
     */
    public function showAction($id)
    {
        $entity = $this->getRepository('CorePlatoBundle:Ingrediente')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ingrediente entity.');
        }
        return array(
            'entity' => $entity,
        );
    }

    /**
     * Displays a form to create a new Ingrediente entity.
     *
     * @Route("/new", name="admin_ingrediente_new")
     * @Template()
     * @Secure(roles="ROLE_INGREDIENTE_NEW")
     */
    public function newAction()
    {
        $entity = new Ingrediente();
        $form   = $this->createForm(new IngredienteType(), $entity);        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity Ingrediente is saved.');
                return $this->redirect($this->generateUrl('admin_ingrediente_show', array('id' => $entity->getId())));
            }
        }
        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Ingrediente entity.
     *
     * @Route("/{id}/edit", name="admin_ingrediente_edit")
     * @Template()
     * @Secure(roles="ROLE_INGREDIENTE_EDIT")
     */
    public function editAction($id)
    {
        $entity = $this->getRepository('CorePlatoBundle:Ingrediente')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ingrediente entity.');
        }
        $form = $this->createForm(new IngredienteType(), $entity);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity Ingrediente is saved.');
                return $this->redirect($this->generateUrl('admin_ingrediente_show', array('id' => $entity->getId())));
            }
        } 
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes a Ingrediente entity.
     *
     * @Route("/{id}/delete", name="admin_ingrediente_delete")
     * @Secure(roles="ROLE_INGREDIENTE_DELETE")
     */
    public function deleteAction($id)
    {
        $entity = $this->getRepository('CorePlatoBundle:Ingrediente')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ingrediente entity.');
        }
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
        $this->setFlash('notice', 'The entity Ingrediente is deleted.');
        return $this->redirect($this->generateUrl('admin_ingrediente'));
    }
    
    /**
     * Batch actions for Ingrediente entity.
     *
     * @Route("/batch", name="admin_ingrediente_batch")
     * @Secure(roles="ROLE_INGREDIENTE_DELETE")
     */
    public function batchAction()
    {
        $method = $this->getRequest()->request->get('batch_action');
        if (!$method) {
            $this->setFlash('error', 'Select a action');
            return $this->redirect($this->generateUrl('admin_ingrediente'));
        }
        $method = $method.'Batch';
        
        if (!method_exists($this, $method)) {
            throw new \UnexpectedValueException('The bacth method not defined');
        }
        
        $ids = $this->getRequest()->request->get('ids');
        
        if (!count($ids)) {
            $this->setFlash('error', 'Select a record');
            return $this->redirect($this->generateUrl('admin_ingrediente'));
        }
        
        $this->$method($ids);
        
        return $this->redirect($this->generateUrl('admin_ingrediente'));
    }
        
    protected function deleteBatch($ids)
    {
        $qb = $this->getRepository('CorePlatoBundle:Ingrediente')->createQueryBuilder('Ingrediente');
        $qb->delete()->where($qb->expr()->in('Ingrediente.id', $ids));
        $qb->getQuery()->execute();
        
        $this->getRequest()->getSession()->setFlash('notice', 'The records are deleted.');
    }
        
    /**
     * Change Max Per Page.
     *
     * @Route("/changemaxperpage", name="admin_ingrediente_changemaxperpage")
     * @Secure(roles="ROLE_INGREDIENTE_LIST")
     */
    public function changeMaxPerPageAction()
    {
        $this->setSession('maxperpage', $this->get('request')->query->get('cant'));
        $this->setPage(1);
        return $this->redirect($this->generateUrl('admin_ingrediente'));
    }
    
    /**
     * Change Sort.
     *
     * @Route("/{field}/{order}/short", name="admin_ingrediente_sort")
     * @Secure(roles="ROLE_INGREDIENTE_LIST")
     */
    public function sortAction($field, $order)
    {
        $this->setPage(1);
        $this->setSort(array(
            'field' => $field,
            'order' => $order,
            'next'  => $order == 'ASC'? 'DESC': 'ASC',
        ));
        return $this->redirect($this->generateUrl('admin_ingrediente'));
    }
    
    protected function getPager()
    {
        $filter_form = $this->getFilterForm();        
        $qb = $this->getRepository('CorePlatoBundle:Ingrediente')
                   ->ingredientesQuery()
        ;
        $sort = $this->getSort();
        if ($sort) {
            $qb->add('orderBy', sprintf('%s.%s %s', $qb->getRootAlias(), $sort['field'], $sort['order']));
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
        $filter_form = $this->get('form.factory')->create(new IngredienteFilterType());        
        $filter_form->bind($this->getFilters());
        return $filter_form;
    }    
}
