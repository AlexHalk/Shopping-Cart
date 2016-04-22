<?php

namespace Pascal\ShopTestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="Pascal\ShopTestBundle\Entity\ProductRepository")
 */
class Product
{
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Description", mappedBy="product")
     */
    private $descriptions;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products", fetch="EAGER")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $category;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Quantity", mappedBy="product")
     */
    private $quantitiesPRO;

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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * Creates Constructer for ArrayCollection
     */
    public function __construct() {
        $this->descriptions = new ArrayCollection();
        $this->quantitiesPRO = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Add descriptions
     *
     * @param \Pascal\ShopTestBundle\Entity\Description $descriptions
     * @return Product
     */
    public function addDescription(\Pascal\ShopTestBundle\Entity\Description $descriptions)
    {
        $this->descriptions[] = $descriptions;

        return $this;
    }

    /**
     * Remove descriptions
     *
     * @param \Pascal\ShopTestBundle\Entity\Description $descriptions
     */
    public function removeDescription(\Pascal\ShopTestBundle\Entity\Description $descriptions)
    {
        $this->descriptions->removeElement($descriptions);
    }

    /**
     * Get descriptions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDescriptions()
    {
        return $this->descriptions;
    }

    /**
     * Get Quantities
     *
     * @return integer
     */
    public function getQuantities()
    {
        return $this->quantities;
    }

    /**
     * Set category
     *
     * @param \Pascal\ShopTestBundle\Entity\Category $category
     * @return Product
     */
    public function setCategory(\Pascal\ShopTestBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Pascal\ShopTestBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add quantitiesPRO
     *
     * @param \Pascal\ShopTestBundle\Entity\Quantity $quantitiesPRO
     * @return Product
     */
    public function addQuantitiesPRO(\Pascal\ShopTestBundle\Entity\Quantity $quantitiesPRO)
    {
        $this->quantitiesPRO[] = $quantitiesPRO;

        return $this;
    }

    /**
     * Remove quantitiesPRO
     *
     * @param \Pascal\ShopTestBundle\Entity\Quantity $quantitiesPRO
     */
    public function removeQuantitiesPRO(\Pascal\ShopTestBundle\Entity\Quantity $quantitiesPRO)
    {
        $this->quantitiesPRO->removeElement($quantitiesPRO);
    }

    /**
     * Get quantitiesPRO
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuantitiesPRO()
    {
        return $this->quantitiesPRO;
    }

    public function __toString()
    {
        // return $this->getQuantitiesPRO();
        return $this->getName();
        // return $this->getDescriptions();
    }
}
