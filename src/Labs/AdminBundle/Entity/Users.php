<?php

/**
 * Created by PhpStorm.
 * User: raphael
 * Date: 02/11/2016
 * Time: 17:03
 */

namespace Labs\AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */

class Users extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="Labs\AdminBundle\Entity\Post", mappedBy="user")
     */
    protected $posts;

    public function __construct()
    {
        parent::__construct();
        $this->posts = new ArrayCollection();
    }


    /**
     * Add post
     *
     * @param \Labs\AdminBundle\Entity\Post $post
     *
     * @return Users
     */
    public function addPost(\Labs\AdminBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \Labs\AdminBundle\Entity\Post $post
     */
    public function removePost(\Labs\AdminBundle\Entity\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }
}
