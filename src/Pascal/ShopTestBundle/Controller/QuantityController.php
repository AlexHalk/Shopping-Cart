<?php

namespace Pascal\ShopTestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pascal\ShopTestBundle\Entity\Quantity;
use Pascal\ShopTestBundle\Form\QuantityType;

/**
 * Quantity controller.
 *
 * @Route("/quantity")
 */
class QuantityController extends Controller
{

    /**
     * Lists all Quantity entities.
     *
     * @Route("/", name="quantity")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PascalShopTestBundle:Quantity')->findAll();

        if ($this->checkAdminLogin()) {
            throw $this->createNotFoundException('No Access.');
        }

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Creates a new Quantity entity.
     *
     * @Route("/", name="quantity_create")
     * @Method("POST")
     * @Template("PascalShopTestBundle:Quantity:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Quantity();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('quantity_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Quantity entity.
     *
     * @param Quantity $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Quantity $entity)
    {
        $form = $this->createForm(new QuantityType(), $entity, array(
            'action' => $this->generateUrl('quantity_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Quantity entity.
     *
     * @Route("/new", name="quantity_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
//USELESS 'C'RUD FUNCTION-------------------------------------
        $entity = new Quantity();
        $form   = $this->createCreateForm($entity);

        if ($this->checkAdminLogin()) {
            throw $this->createNotFoundException('No Access.');
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Quantity entity.
     *
     * @Route("/{id}", name="quantity_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PascalShopTestBundle:Quantity')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quantity entity.');
        } elseif ($this->checkAdminLogin()) {
            throw $this->createNotFoundException('No Access.');
        } else {
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity'      => $entity,
                'delete_form' => $deleteForm->createView(),
            );
        }
    }

    /**
     * Displays a form to edit an existing Quantity entity.
     *
     * @Route("/{id}/edit", name="quantity_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PascalShopTestBundle:Quantity')->find($id);

        if ($entity->getUsercart()->getSubmitted(true) || ($entity->getProduct() == NULL)) {
            $this->addFlash('notice', 'You are unable to update a qauntity of a bought product/cart or if a product is null.');
            return $this->redirect($this->generateUrl('quantity'));
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quantity entity.');
        } elseif ($this->checkAdminLogin()) {
            throw $this->createNotFoundException('No Access.');
        } else {
            $editForm = $this->createEditForm($entity);
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        }
    }

    /**
    * Creates a form to edit a Quantity entity.
    *
    * @param Quantity $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Quantity $entity)
    {
        $form = $this->createForm(new QuantityType(), $entity, array(
            'action' => $this->generateUrl('quantity_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Quantity entity.
     *
     * @Route("/{id}", name="quantity_update")
     * @Method("PUT")
     * @Template("PascalShopTestBundle:Quantity:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PascalShopTestBundle:Quantity')->find($id);

        if (!$entity || $this->checkAdminLogin()) {
            throw $this->createNotFoundException('Unable to find Quantity entity/No Access.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('quantity_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Quantity entity.
     *
     * @Route("/{id}", name="quantity_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {

//USELESS CRU'D' FUNCTION-------------------------------------
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PascalShopTestBundle:Quantity')->find($id);

            if (!$entity || $this->checkAdminLogin()) {
                throw $this->createNotFoundException('Unable to find Quantity entity/No Access.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('quantity'));
    }

    /**
     * Creates a form to delete a Quantity entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('quantity_delete', array('id' => $id)))
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
