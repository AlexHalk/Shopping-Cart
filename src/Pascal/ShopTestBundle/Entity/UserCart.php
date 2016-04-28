<?php

namespace Pascal\ShopTestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * UserCart
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pascal\ShopTestBundle\Entity\UserCartRepository")
 */
class UserCart
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
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="time", nullable=true)
     */
    private $timestamp;

    /**
     * @var boolean
     *
     * @ORM\Column(name="submitted", type="boolean")
     */
    private $submitted;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userCarts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity = "Quantity", mappedBy="userCart", cascade={"persist"})
     */
    private $quantities;

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
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return UserCart
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        // $this->timestamp = new \DateTime("now");


        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set submitted
     *
     * @param boolean $submitted
     * @return UserCart
     */
    public function setSubmitted($submitted)
    {
        $this->submitted = $submitted;

        return $this;
    }

    /**
     * Get submitted
     *
     * @return boolean 
     */
    public function getSubmitted()
    {
        return $this->submitted;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quantities = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set user
     *
     * @param \Pascal\ShopTestBundle\Entity\User $user
     * @return UserCart
     */
    public function setUser(\Pascal\ShopTestBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Pascal\ShopTestBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add quantites
     *
     * @param \Pascal\ShopTestBundle\Entity\Quantity $quantites
     * @return UserCart
     */
    public function addQuantity(\Pascal\ShopTestBundle\Entity\Quantity $quantities)
    {
        $this->quantities[] = $quantities;

        return $this;
    }

    /**
     * Remove quantites
     *
     * @param \Pascal\ShopTestBundle\Entity\Quantity $quantites
     */
    public function removeQuantity(\Pascal\ShopTestBundle\Entity\Quantity $quantities)
    {
        $this->quantities->removeElement($quantities);
    }

    /**
     * Get quantites
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuantities()
    {
        return $this->quantities;
    }

    // /**
    //  * Converts Quantities to a Viewable String
    //  */
    // public function __toString() 
    // {
    //     return $this->timestamp();
    // }
}
