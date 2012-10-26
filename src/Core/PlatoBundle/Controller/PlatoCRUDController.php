<?php

namespace Core\PlatoBundle\Controller;

use CULabs\AdminBundle\Controller\CRUDController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Core\PlatoBundle\Entity\Plato;
use Core\PlatoBundle\Form\PlatoType;
use Core\PlatoBundle\Form\PlatoIngredienteType;
use Core\PlatoBundle\Filter\PlatoFilterType;
use Core\PlatoBundle\Entity\PlatoIngrediente;

/**
 * Plato controller.
 *
 * @Route("/admin/plato")
 */
class PlatoCRUDController extends CRUDController
{
    /**
     * Lists all Plato entities.
     *
     * @Route("", name="admin_plato")
     * @Template()
     * @Secure(roles="ROLE_PLATO_LIST")
     */
    public function indexAction()
    {        
        $page = $this->get('request')->query->get('page', $this->getPage());
        $this->setPage($page);
        $pager = $this->getPager();
        if ($this->get('request')->isXmlHttpRequest()) {
            return $this->render('CorePlatoBundle:PlatoCRUD:list.html.twig', array(
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
     * Filter Plato entities.
     *
     * @Route("/filter", name="admin_plato_filter")
     * @Method("post")     
     */
    public function filterAction()
    {        
        if ($this->getRequest()->request->get('action_reset')) {
            $this->setFilters(array());
            return $this->redirect($this->generateUrl('admin_plato'));
        }        
        $filter_form = $this->get('form.factory')->create(new PlatoFilterType());        
        $filter_form->bindRequest($this->get('request'));        
        if ($filter_form->isValid()) {
            $this->setPage(1);
            $this->setFilters($filter_form->getData());
            return $this->redirect($this->generateUrl('admin_plato'));
        }        
        return $this->render('CorePlatoBundle:Plato:index.html.twig', array(
            'filter' => $filter_form->createView(),
            'pager'  => $this->getPager(),
        ));
    }
    
    /**
     * Finds and displays a Plato entity.
     *
     * @Route("/{id}/show", name="admin_plato_show")
     * @Template()
     * @Secure(roles="ROLE_PLATO_SHOW")
     */
    public function showAction($id)
    {
        $entity = $this->getRepository('CorePlatoBundle:Plato')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plato entity.');
        }
        $ingredientes = $this->getRepository('CorePlatoBundle:Ingrediente')
                             ->findIngredientesQuery($entity)
                             ->getQuery()
                             ->getResult()
                        ;
        return array(
            'entity'       => $entity,
            'ingredientes' => $ingredientes,
        );
    }

    /**
     * Displays a form to create a new Plato entity.
     *
     * @Route("/new", name="admin_plato_new")
     * @Template()
     * @Secure(roles="ROLE_PLATO_NEW")
     */
    public function newAction()
    {
        $entity = new Plato();
        $entity->addPlatoIngrediente(new PlatoIngrediente());
        
        $form   = $this->createForm(new PlatoType(), $entity);        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity Plato is saved.');
                return $this->redirect($this->generateUrl('admin_plato_show', array('id' => $entity->getId())));
            }
        }
        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Plato entity.
     *
     * @Route("/{id}/edit", name="admin_plato_edit")
     * @Template()
     * @Secure(roles="ROLE_PLATO_EDIT")
     */
    public function editAction($id)
    {
        $entity = $this->getRepository('CorePlatoBundle:Plato')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plato entity.');
        }
        
        $ingredientes_originales = array();
        foreach ($entity->getPlatoIngredientes() as $item) {
            $ingredientes_originales[] = $item;
        }
        
        $form = $this->createForm(new PlatoType(), $entity, array(
            'foto_reqerida' => false,
        ));
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                
                $em = $this->getEntityManager();
                
                foreach ($entity->getPlatoIngredientes() as $item) {
                    foreach ($ingredientes_originales as $key => $toDel) {
                        if ($toDel->getId() === $item->getId())
                            unset($ingredientes_originales[$key]);
                    }
                }
                
                foreach ($ingredientes_originales as $item) {
                    
                    $entity->removePlatoIngrediente($item);
                    $em->remove($item);
                }
                
                $em->persist($entity);
                $em->flush();
                $this->setFlash('notice', 'The entity Plato is saved.');
                return $this->redirect($this->generateUrl('admin_plato_show', array('id' => $entity->getId())));
            }
        } 
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes a Plato entity.
     *
     * @Route("/{id}/delete", name="admin_plato_delete")
     * @Secure(roles="ROLE_PLATO_DELETE")
     */
    public function deleteAction($id)
    {
        $entity = $this->getRepository('CorePlatoBundle:Plato')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plato entity.');
        }
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
        $this->setFlash('notice', 'The entity Plato is deleted.');
        return $this->redirect($this->generateUrl('admin_plato'));
    }
    
    /**
     * Batch actions for Plato entity.
     *
     * @Route("/batch", name="admin_plato_batch")
     * @Secure(roles="ROLE_PLATO_DELETE")
     */
    public function batchAction()
    {
        $method = $this->getRequest()->request->get('batch_action');
        if (!$method) {
            $this->setFlash('error', 'Select a action');
            return $this->redirect($this->generateUrl('admin_plato'));
        }
        $method = $method.'Batch';
        
        if (!method_exists($this, $method)) {
            throw new \UnexpectedValueException('The bacth method not defined');
        }
        
        $ids = $this->getRequest()->request->get('ids');
        
        if (!count($ids)) {
            $this->setFlash('error', 'Select a record');
            return $this->redirect($this->generateUrl('admin_plato'));
        }
        
        $this->$method($ids);
        
        return $this->redirect($this->generateUrl('admin_plato'));
    }
        
    protected function deleteBatch($ids)
    {
        $qb = $this->getRepository('CorePlatoBundle:Plato')->createQueryBuilder('Plato');
        $qb->delete()->where($qb->expr()->in('Plato.id', $ids));
        $qb->getQuery()->execute();
        
        $this->getRequest()->getSession()->setFlash('notice', 'The records are deleted.');
    }
        
    /**
     * Change Max Per Page.
     *
     * @Route("/changemaxperpage", name="admin_plato_changemaxperpage")
     * @Secure(roles="ROLE_PLATO_LIST")
     */
    public function changeMaxPerPageAction()
    {
        $this->setSession('maxperpage', $this->get('request')->query->get('cant'));
        $this->setPage(1);
        return $this->redirect($this->generateUrl('admin_plato'));
    }
    
    /**
     * Change Sort.
     *
     * @Route("/{field}/{order}/short", name="admin_plato_sort")
     * @Secure(roles="ROLE_PLATO_LIST")
     */
    public function sortAction($field, $order)
    {
        $this->setPage(1);
        $this->setSort(array(
            'field' => $field,
            'order' => $order,
            'next'  => $order == 'ASC'? 'DESC': 'ASC',
        ));
        return $this->redirect($this->generateUrl('admin_plato'));
    }
    
    protected function getPager()
    {
        $filter_form = $this->getFilterForm();        
        $qb = $this->getRepository('CorePlatoBundle:Plato')
                   ->createQueryBuilder('Plato')
        ;
        $sort = $this->getSort();
        if ($sort) {
            $qb->add('orderBy', sprintf('Plato.%s %s', $sort['field'], $sort['order']));
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
        $filter_form = $this->get('form.factory')->create(new PlatoFilterType());        
        $filter_form->bind($this->getFilters());
        return $filter_form;
    }    
}
