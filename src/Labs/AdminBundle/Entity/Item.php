<?php

namespace Labs\AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="Labs\AdminBundle\Repository\ItemRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Item
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
     * @Assert\NotBlank(message="entrez le nom de la sous rubrique")
     */
    protected $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="date")
     */
    protected $created;

    /**
     * @var bool
     * @Assert\NotBlank(message="Le status de l'item doit être selectionnez avant de contunuer")
     * @ORM\Column(name="online", type="boolean", nullable=true)
     */
    protected $online;

    /**
     * @Gedmo\Slug(fields={"name", "id"})
     * @ORM\Column(length=128, unique=true)
     */
    protected $slug;

    /**
     * @var
     * @Assert\NotBlank(message="L'item doit être lié a une rubrique")
     * @ORM\ManyToOne(targetEntity="Labs\AdminBundle\Entity\Section", inversedBy="items")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    protected $section;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="Labs\AdminBundle\Entity\Post", mappedBy="item")
     */
    protected $posts;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="Labs\AdminBundle\Entity\Format", mappedBy="item")
     */
    protected $formats;


    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->posts = new ArrayCollection();
        $this->formats = new ArrayCollection();
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
     * @return Item
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Item
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set online
     *
     * @param boolean $online
     *
     * @return Item
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
     * Set section
     *
     * @param \Labs\AdminBundle\Entity\Section $section
     *
     * @return Item
     */
    public function setSection(\Labs\AdminBundle\Entity\Section $section = null)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return \Labs\AdminBundle\Entity\Section
     */
    public function getSection()
    {
        return $this->section;
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

    /**
     * Add post
     *
     * @param \Labs\AdminBundle\Entity\Post $post
     *
     * @return Item
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

    /**
     * Add format
     *
     * @param \Labs\AdminBundle\Entity\Format $format
     *
     * @return Item
     */
    public function addFormat(\Labs\AdminBundle\Entity\Format $format)
    {
        $this->formats[] = $format;

        return $this;
    }

    /**
     * Remove format
     *
     * @param \Labs\AdminBundle\Entity\Format $format
     */
    public function removeFormat(\Labs\AdminBundle\Entity\Format $format)
    {
        $this->formats->removeElement($format);
    }

    /**
     * Get formats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormats()
    {
        return $this->formats;
    }
}
