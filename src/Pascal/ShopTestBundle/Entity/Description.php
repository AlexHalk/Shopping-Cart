<?php

namespace Pascal\ShopTestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collection\ArrayCollection;

/**
 * Description
 *
 * @ORM\Table(name="descriptions")
 * @ORM\Entity(repositoryClass="Pascal\ShopTestBundle\Entity\DescriptionRepository")
 */
class Description
{

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="descriptions")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $product;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="productDesciption", type="string", length=255)
     */
    private $productDesciption;


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
     * Set productDesciption
     *
     * @param string $productDesciption
     * @return Description
     */
    public function setProductDesciption($productDesciption)
    {
        $this->productDesciption = $productDesciption;

        return $this;
    }

    /**
     * Get productDesciption
     *
     * @return string 
     */
    public function getProductDesciption()
    {
        return $this->productDesciption;
    }

    /**
     * Set product
     *
     * @param \Pascal\ShopTestBundle\Entity\Product $product
     * @return Description
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

    public function __toString()
    {
        // return $this->getQuantitiesPRO();
        // return $this->getName();
        return $this->productDesciption;
    }
}
