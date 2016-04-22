<?php

namespace Pascal\ShopTestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
     function indexAction() {
	     if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
	         return $this->redirectToRoute('product_viewAdmin');
	     } else {
	         return $this->redirectToRoute('product');
	     }
	}
}
