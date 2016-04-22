<?php

namespace Pascal\ShopTestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Quantity
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pascal\ShopTestBundle\Entity\QuantityRepository")
 */
class Quantity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="UserCart", inversedBy="quantities", cascade={"persist"})
     * @ORM\JoinColumn(name="userCart_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $userCart;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="quantitiesPRO", cascade={"persist"})
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $product;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return Quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set userCart
     *
     * @param \Pascal\ShopTestBundle\Entity\UserCart $userCart
     * @return Quantity
     */
    public function setUserCart(\Pascal\ShopTestBundle\Entity\UserCart $userCart = null)
    {
        $this->userCart = $userCart;

        return $this;
    }

    /**
     * Get userCart
     *
     * @return \Pascal\ShopTestBundle\Entity\UserCart 
     */
    public function getUserCart()
    {
        return $this->userCart;
    }

    /**
     * Set product
     *
     * @param \Pascal\ShopTestBundle\Entity\Product $product
     * @return Quantity
     */
    public function setProduct(\Pascal\ShopTestBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Pascal\ShopTestBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
