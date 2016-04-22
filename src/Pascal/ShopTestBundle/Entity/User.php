<?php

namespace Pascal\ShopTestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity()
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="UserCart", mappedBy="user")
     */
    private $userCarts;

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
     * Add userCarts
     *
     * @param \Pascal\ShopTestBundle\Entity\UserCart $userCarts
     * @return User
     */
    public function addUserCart(\Pascal\ShopTestBundle\Entity\UserCart $userCarts)
    {
        $this->userCarts[] = $userCarts;

        return $this;
    }

    /**
     * Remove userCarts
     *
     * @param \Pascal\ShopTestBundle\Entity\UserCart $userCarts
     */
    public function removeUserCart(\Pascal\ShopTestBundle\Entity\UserCart $userCarts)
    {
        $this->userCarts->removeElement($userCarts);
    }

    /**
     * Get userCarts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserCarts()
    {
        return $this->userCarts;
    }
}
