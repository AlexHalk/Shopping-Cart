<?php

namespace Pascal\ShopTestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pascal\ShopTestBundle\Entity\Product;
use Pascal\ShopTestBundle\Form\ProductType;
use Pascal\ShopTestBundle\Entity\UserCart;
use Pascal\ShopTestBundle\Entity\Quantity;

/**
 * Product controller.
 *
 * @Route("/product")
 */
class ProductController extends Controller {   

    /**
     * This function gets the Category association which is related back to the Product Entity.
     * In the view index.html.twig for Product Entity, a check is performed to see if a user is logged in.
     * Then categories is iterated through, split into tabs, and the products for the tab it relates to
     *    are displayed.
     *
     * @Route("/", name="product")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {

        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('PascalShopTestBundle:Category')->findAll();

        $user = $this->getUser();

        return array(
            'user'  => $user,
            'categories' => $categories,
        );
    }

    /**
     * This function relates a newly created product.
     *
     * @Route("/", name="product_create")
     * @Method("POST")
     * @Template("PascalShopTestBundle:Product:new.html.twig")
     */
    public function createAction(Request $request) {

        $entity = new Product();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('product_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * This function created the form to be used by an Admin to create a new product.
     *
     * @param Product $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Product $entity) {

        $form = $this->createForm(new ProductType(), $entity, array(
            'action' => $this->generateUrl('product_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * This function displays the form to create a new product.
     *
     * @Route("/new", name="product_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {

        $entity = new Product();
        $form   = $this->createCreateForm($entity);

        if ($this->checkAdminLogin()) {
            throw $this->createNotFoundException('Admin Only Access');
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }


    /**
     * This function displays a form to edit an existing product accessaable only by an Admin.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PascalShopTestBundle:Product')->find($id);

        if (!$entity || $this->checkAdminLogin()) {
            throw $this->createNotFoundException('Unable to find Product entity/Admin Only Access.');
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
    * This function creates a form to edit the product.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Product $entity) {

        $form = $this->createForm(new ProductType(), $entity, array(
            'action' => $this->generateUrl('product_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * This function performs the action of updating a edited product
     *
     * @Route("/{id}", name="product_update")
     * @Method("PUT")
     * @Template("PascalShopTestBundle:Product:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PascalShopTestBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('product_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    /**
     * This function deletes a product to be perform only by an Admin.
     *
     * @Route("/{id}", name="product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {

        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PascalShopTestBundle:Product')->find($id);

            if (!$entity || $this->checkAdminLogin()) {
                throw $this->createNotFoundException('Unable to find Product entity/Admin Access Only.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('product_view'));
    }

    /**
     * This function creates a form/uses dql to delete a product.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

/*---------------------------------------------------------------------------------------------------*/
    /**
     * This is a helper private function to calculate the total cost of the products added to the users cart.
     */
    private function getTotalCost($cart, $totalCostOfAllProducts) {

        $price = 0;
        $quantity = 0;
        $prodsInCart = $cart->getQuantities();

        foreach ($prodsInCart as $key => $value) {
            $prodsInCartCostPrice = $value->getProduct()->getPrice();
            $prodsInCartQuantity = $value->getQuantity();
            $totalCostOfAllProducts += $prodsInCartCostPrice * $prodsInCartQuantity;
        }
        return $totalCostOfAllProducts;

    }
/*--------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------*/
    /**
     * This is a private helper function to check the logged in users credentials...Particulary if the user
     *    the user is just a user and has NO special permissions.
     */
    private function checkUserLogin() {

        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $this->addFlash('notice', 'Login to create a cart');
            return true;
        }

        return false;
    }

/*--------------------------------------------------------------------------------------------------*/
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

    /** 
     * This function will add a selected product to the users cart. If no one is logged in then no cart can be created.
     *    Once a user is logged in then once they select a product to add, a new shopping cart is created for them. 
     *    At this point, if the user adds the same product then the quantity field is increased by '1', otherwise, the
     *    selected product is added normally to their existing shopping cart.
     *
     * @Route("/{id}/addToCart", name="product_addToCart")
     * @Method("GET")
     * @Template()
     */
    public function addToCartAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();

        $productBeingAddedToCart = $em->getRepository('PascalShopTestBundle:Product')->find($id);
        $cart = $em->getRepository('PascalShopTestBundle:UserCart')->findOneBy(['user' => $this->getUser(), 'submitted' => false]);

        if ($this->checkUserLogin()) {
            $this->addFlash('notice', 'You have to login.');
            return $this->redirectToRoute('product');
        }

        if (is_null($cart) || $cart->getSubmitted(true)) {
            $cart = new UserCart();
            $quantity = new Quantity();
            $cart->setTimestamp(new \DateTime()); // Set Time Product was Added
            $quantity->setQuantity(1);   // Set Quantity Purchased
            $cart->setSubmitted(false); // Set Submitted
            $cart->setUser($this->getUser());  // Sets the User ONCE
            $quantity->setProduct($productBeingAddedToCart);
            $cart->addQuantity($quantity);    //  Add Quantity ONCE
            $quantity->setUserCart($cart);   //   Create a UserCart ONCE                
            $em->persist($productBeingAddedToCart);
            $em->persist($cart);
            $em->persist($quantity);
            $em->flush();
            $this->addFlash('notice', 'A Cart Has Been Created & The Product: '.$productBeingAddedToCart->getName().' Has Been Added To Your Cart!');
        }
        else {
            $getEverythingInCart = $cart->getQuantities();
            $foundProduct = false;
            foreach ($getEverythingInCart as $key => $value) {
                $getAllProductNamesInCart = $value->getProduct()->getName();
                $getAllProductsInCartENTITY = $value->getProduct();
                $getQuantityOfProduct = $value->getQuantity();
                if ($getAllProductNamesInCart === $productBeingAddedToCart->getName()) {
                    $foundProduct = true;
                    // We found the product! Update the Quantity of the Product in the Cart:
                    $value->setQuantity($getQuantityOfProduct + 1);
                    $em->persist($value);
                    break;
                } //Ends foreach if
            } //Ends foreach

            if (!$foundProduct) {
                // Add a New Product to the Cart:
                $quantity = new Quantity();
                $quantity->setQuantity(1);   // Set Quantity Purchased
                $quantity->setProduct($productBeingAddedToCart);
                $cart->addQuantity($quantity);    //  Add Quantity ONCE
                $quantity->setUserCart($cart);   //   Create a UserCart ONCE                
                $em->persist($productBeingAddedToCart);
                $em->persist($quantity);            
                $cart->setTimestamp(new \DateTime()); // Set Time Product was Added
                $cart->setSubmitted(false); // Set Submitted
                $cart->setUser($this->getUser());  // Sets the User ONCE
            }

            $em->persist($cart);
            $em->flush();
            $this->addFlash('notice', 'The Product: '.$productBeingAddedToCart->getName().' Has Been Added To Your Cart!');
        }

        return $this->redirectToRoute('product');
    }

    /**
     * This function will only show what the user has added to their shopping cart. 
     *    This page is not accessable without a cart in existance. In showCart.html.twig for Product Entity
     *    the option to increase or decrease the quantity of a product is available using jQuery/Ajax. 
     * 
     * @Route("/showCart", name="product_showCart")
     * @METHOD("GET")
     * @Template()
     */
    public function showCartAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($this->checkUserLogin()) {
            return $this->redirectToRoute('product');
        }

        $cart = $em->getRepository('PascalShopTestBundle:UserCart')->findOneBy(['user' => $this->getUser(), 'submitted' => false]);

        if (!$cart) {
            $this->addFlash('notice', 'There is no cart to use, please select a product & a cart will be created for you.');
            return $this->redirectToRoute('product');
        }

        $totalCostOfAllProducts = 0;

        $totalCostOfAllProducts = $this->getTotalCost($cart, $totalCostOfAllProducts);

        return array(
            'user' => $cart->getUser(),
            'cart' => $cart,
            'quantities' => $cart->getQuantities(),
            'totalCostOfAllProducts'    => $totalCostOfAllProducts,
        );
    }

    /**
     * This function updates the quantity field using ajax/jQuery request from showCart twig file
     *
     * @Route("/quantityUpdate", name="product_quantityUpdate")
     * @Method("POST")
     * @Template()
     */
    public function quantityUpdateAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $quantity = $em->getRepository('PascalShopTestBundle:Quantity')->findOneBy(['id' => $request->get('quantityId')]);

        $quantity->setQuantity($_POST['quantity']);
        $em->persist($quantity);
        $em->flush();

        return new Response ("Quantity has been successfully updated!");
    }

    /**
     * This function simply displays a page showing all of the products bought. 
     *    Once a user reaches this page, their shopping cart is emptied.
     *
     * @Route("/bought", name="product_bought")
     * @Method("GET")
     * @Template()
     */
    public function boughtAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $cart = $em->getRepository('PascalShopTestBundle:UserCart')->findOneBy(['user' => $this->getUser(), 'submitted' => false]);

        $totalCostOfAllProducts = 0;

        if (count($cart->getQuantities()) < 1) {
            $this->addFlash('notice', 'There Is Nothing In Your Shopping Cart To Buy.');
            return $this->redirectToRoute('product_showCart');
        }

        $totalCostOfAllProducts = $this->getTotalCost($cart, $totalCostOfAllProducts);

        $cart->getSubmitted();
        $cart->setSubmitted(true);
        $sub = $cart->getSubmitted();   

        if ($sub == true) {
            $em->persist($cart);
            $em->flush();         
        }

        return array(
            'user' => $user,
            'quantity' => $cart->getQuantities(),
            'totalCostOfAllProducts' => $totalCostOfAllProducts,
        );
    }

    /**
     * This function is only accessable with an Admin login. In viewAdmin.html.twig for the Product Entity
     *    two tables are shown: (1) for bought products (where submitted is true),  
     *    and (2) for shopping carts currently in use (where submitted is false). The Admin has the ability to edit or delete the
     *    product from a users cart as well as the ability to create a new product all together.  
     *
     * @Route("/viewAdmin", name="product_viewAdmin")
     * @Method("GET")
     * @Template()
     */
    public function viewAdminAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $username = $this->getUser();

        $allOfTheCarts = $em->getRepository('PascalShopTestBundle:UserCart')->findAll();

        if ($this->checkAdminLogin()) {
            throw $this->createNotFoundException('No Access.');
        }

        return array(
            'username'      => $username,
            'allOfTheCarts' => $allOfTheCarts,
        );
    }

    /**
     * This function contains the ability to remove a product from the users shopping cart entirely.
     *
     * @Route("/{id}/remove", name="product_remove")
     * @METHOD("GET")
     * @Template()
     */
    public function removeAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('PascalShopTestBundle:Product')->find($id);

        $cart = $em->getRepository('PascalShopTestBundle:UserCart')->findOneBy(['user' => $this->getUser(), 'submitted' => false]);

        $quantity = $em->getRepository('PascalShopTestBundle:Quantity')->findOneBy(['product' => $product->getId(), 'userCart' => $cart->getId()]);

        if (isset($product) || isset($cart) || isset($quantity)) {
            $em->remove($quantity);
            $em->flush();
            $this->addFlash('notice', 'The product: '.$product->getName().' was removed!');
        }

        return $this->redirectToRoute('product_showCart');
    }

    /**
     * This function finds and displays a Product entity.
     *
     * @Route("/{id}", name="product_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PascalShopTestBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        } elseif ($this->checkAdminLogin()) {
            throw $this->createNotFoundException('No Access To This Page.');
        } else {
            $descriptions = $entity->getDescriptions();
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'descriptions'=> $descriptions,
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

} //ENDS CLASS

  