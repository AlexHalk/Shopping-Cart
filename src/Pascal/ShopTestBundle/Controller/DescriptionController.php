<?php

namespace Pascal\ShopTestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pascal\ShopTestBundle\Entity\Description;
use Pascal\ShopTestBundle\Form\DescriptionType;

/**
 * Description controller.
 *
 * @Route("/description")
 */
class DescriptionController extends Controller
{

    /**
     * Lists all Description entities.
     *
     * @Route("/", name="description")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PascalShopTestBundle:Description')->findAll();

        if ($this->checkAdminLogin()) {
            throw $this->createNotFoundException('No Access To This Page.');
        }

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Description entity.
     *
     * @Route("/", name="description_create")
     * @Method("POST")
     * @Template("PascalShopTestBundle:Description:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Description();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('description_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Description entity.
     *
     * @param Description $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Description $entity)
    {
        $form = $this->createForm(new DescriptionType(), $entity, array(
            'action' => $this->generateUrl('description_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Description entity.
     *
     * @Route("/new", name="description_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Description();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Description entity.
     *
     * @Route("/{id}", name="description_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PascalShopTestBundle:Description')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Description entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Description entity.
     *
     * @Route("/{id}/edit", name="description_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PascalShopTestBundle:Description')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Description entity.');
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
    * Creates a form to edit a Description entity.
    *
    * @param Description $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Description $entity)
    {
        $form = $this->createForm(new DescriptionType(), $entity, array(
            'action' => $this->generateUrl('description_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Description entity.
     *
     * @Route("/{id}", name="description_update")
     * @Method("PUT")
     * @Template("PascalShopTestBundle:Description:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PascalShopTestBundle:Description')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Description entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('description_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    /**
     * Deletes a Description entity.
     *
     * @Route("/{id}", name="description_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PascalShopTestBundle:Description')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Description entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('description'));
    }

    /**
     * Creates a form to delete a Description entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('description_delete', array('id' => $id)))
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
