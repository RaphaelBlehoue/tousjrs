<?php

namespace Labs\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="Labs\AdminBundle\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Post
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="L'article doit avoir un titre")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="legend", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="L'article doit avoir une legend")
     */
    protected $legend;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     * @Assert\NotBlank(message="Votre article n'a aucun contenu, veuillez entrer des informations avant de continuer")
     * @Assert\Length(
     *      min = 100,
     *      minMessage = "Votre article doit comporter plus de 100 caractères",
     * )
     */
    protected $content;

    /**
     * @var bool
     * @ORM\Column(name="online", type="boolean", nullable=true)
     */
    protected $online;

    /**
     * @var bool
     *
     * @ORM\Column(name="draft", type="boolean", nullable=true)
     */
    protected $draft;

    /**
     * @Gedmo\Slug(fields={"name", "id"})
     * @ORM\Column(length=128, unique=true)
     */
    protected $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;
    

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="Labs\AdminBundle\Entity\Media", mappedBy="post", cascade={"remove","persist"})
     */
    protected $medias;

    /**
     * @var
     * @Assert\NotBlank(message="Votre article doit faire partir d'une sous-rubrique, avant de continuer")
     * @ORM\ManyToOne(targetEntity="Labs\AdminBundle\Entity\Item", inversedBy="posts")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    protected  $item;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Labs\AdminBundle\Entity\Users", inversedBy="posts")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    protected $user;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medias = new ArrayCollection();
        $this->created = new \DateTime("now");
        $this->draft = false;
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
     * @return Post
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
     * Set content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set online
     *
     * @param boolean $online
     *
     * @return Post
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
     * Set draft
     *
     * @param boolean $draft
     *
     * @return Post
     */
    public function setDraft($draft)
    {
        $this->draft = $draft;

        return $this;
    }

    /**
     * Get draft
     *
     * @return bool
     */
    public function getDraft()
    {
        return $this->draft;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Post
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
     * Add media
     *
     * @param \Labs\AdminBundle\Entity\Media $media
     *
     * @return Post
     */
    public function addMedia(\Labs\AdminBundle\Entity\Media $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * Remove media
     *
     * @param \Labs\AdminBundle\Entity\Media $media
     */
    public function removeMedia(\Labs\AdminBundle\Entity\Media $media)
    {
        $this->medias->removeElement($media);
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Set item
     *
     * @param \Labs\AdminBundle\Entity\Item $item
     *
     * @return Post
     */
    public function setItem(\Labs\AdminBundle\Entity\Item $item = null)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return \Labs\AdminBundle\Entity\Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set user
     *
     * @param \Labs\AdminBundle\Entity\Users $user
     *
     * @return Post
     */
    public function setUser(\Labs\AdminBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Labs\AdminBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Post
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
     * Get legend
     * 
     * @return string
     */
    public function getLegend()
    {
        return $this->legend;
    }

    /**
     * Set legend
     *
     * @param string $legend
     *
     * @return Post
     */
    public function setLegend($legend)
    {
        $this->legend = $legend;
        
        return $this;
    }
    
    
}
