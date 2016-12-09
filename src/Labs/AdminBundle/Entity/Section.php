<?php

namespace Labs\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Section
 *
 * @ORM\Table(name="section")
 * @ORM\Entity(repositoryClass="Labs\AdminBundle\Repository\SectionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Section
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255, nullable=true)
     */
    protected $color;

    /**
     * @var datetime
     *
     * @ORM\Column(name="created", type="date")
     */
    protected $created;


    /**
     * @var bool
     *
     * @ORM\Column(name="online", type="boolean", nullable=true)
     */
    protected $online;

    /**
     * @var
     * 
     * @ORM\OneToMany(targetEntity="Labs\AdminBundle\Entity\Item", mappedBy="section")
     */
    protected $items;

    /**
     * @Gedmo\Slug(fields={"name", "id"})
     * @ORM\Column(length=128, unique=true)
     */
    protected $slug;
    
    public function __construct()
    {
        $this->created = new \DateTime('now');
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Section
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
     * Set color
     *
     * @param string $color
     *
     * @return Section
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param datetime $created
     *
     * @return Section
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Set online
     *
     * @param boolean $online
     *
     * @return Section
     */
    public function setOnline($online)
    {
        $this->online = $online;

        return $this;
    }

    /**
     * Get online
     *
     * @return bool
     */
    public function getOnline()
    {
        return $this->online;
    }


    /**
     * Add item
     *
     * @param \Labs\AdminBundle\Entity\Item $item
     *
     * @return Section
     */
    public function addItem(\Labs\AdminBundle\Entity\Item $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \Labs\AdminBundle\Entity\Item $item
     */
    public function removeItem(\Labs\AdminBundle\Entity\Item $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Section
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

}
