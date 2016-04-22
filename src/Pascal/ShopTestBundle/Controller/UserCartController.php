<?php

namespace Pascal\ShopTestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pascal\ShopTestBundle\Entity\UserCart;
use Pascal\ShopTestBundle\Form\UserCartType;

/**
 * UserCart controller.
 *
 * @Route("/usercart")
 */
class UserCartController extends Controller
{

    /**
     * Lists all UserCart entities.
     *
     * @Route("/", name="usercart")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PascalShopTestBundle:UserCart')->findAll();

        if ($this->checkAdminLogin()) {
            throw $this->createNotFoundException('No Access.');
        }

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new UserCart entity.
     *
     * @Route("/", name="usercart_create")
     * @Method("POST")
     * @Template("PascalShopTestBundle:UserCart:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new UserCart();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('usercart_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a UserCart entity.
     *
     * @param UserCart $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(UserCart $entity)
    {
        $form = $this->createForm(new UserCartType(), $entity, array(
            'action' => $this->generateUrl('usercart_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new UserCart entity.
     *
     * @Route("/new", name="usercart_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new UserCart();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a UserCart entity.
     *
     * @Route("/{id}", name="usercart_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PascalShopTestBundle:UserCart')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserCart entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing UserCart entity.
     *
     * @Route("/{id}/edit", name="usercart_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PascalShopTestBundle:UserCart')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserCart entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a UserCart entity.
    *
    * @param UserCart $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(UserCart $entity)
    {
        $form = $this->createForm(new UserCartType(), $entity, array(
            'action' => $this->generateUrl('usercart_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing UserCart entity.
     *
     * @Route("/{id}", name="usercart_update")
     * @Method("PUT")
     * @Template("PascalShopTestBundle:UserCart:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PascalShopTestBundle:UserCart')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserCart entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('usercart_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a UserCart entity.
     *
     * @Route("/{id}", name="usercart_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PascalShopTestBundle:UserCart')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find UserCart entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('usercart'));
    }

    /**
     * Creates a form to delete a UserCart entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('usercart_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /*--------------------------------------------------------------------------------------------------*/
    /**
     * This is a private helper function to check the logged in user if they have the credentials to 
     *    be an Admin. Giving them special permission to access the 'Admin View'.
     */
    private function checkAdminLogin() {
    /*

    */
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

    /*--------------------------------------------------------------------------------------------------*/
}
