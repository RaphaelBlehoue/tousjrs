<?php

namespace Labs\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Media
 *
 * @ORM\Table(name="media")
 * @ORM\Entity(repositoryClass="Labs\AdminBundle\Repository\MediaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Media
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
     * @ORM\Column(name="url", type="string", length=255)
     */
    protected $url;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     */
    protected $status;

    /**
     * @var date
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var bool
     *
     * @ORM\Column(name="actived", type="boolean")
     */
    protected $actived;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Labs\AdminBundle\Entity\Post", inversedBy="medias", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    protected $post;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Labs\AdminBundle\Entity\Format", inversedBy="medias", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    protected $format;
    
    
    public function __construct()
    {
        $this->actived = false;
        $this->status = false;
        $this->created = new  \DateTime("now");
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
     * Set url
     *
     * @param string $url
     *
     * @return Media
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Media
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set actived
     *
     * @param boolean $actived
     *
     * @return Media
     */
    public function setActived($actived)
    {
        $this->actived = $actived;

        return $this;
    }

    /**
     * Get actived
     *
     * @return bool
     */
    public function getActived()
    {
        return $this->actived;
    }

    /**
     * Set Post
     *
     * @param \Labs\AdminBundle\Entity\Post $post
     *
     * @return Media
     */
    public function setPost(\Labs\AdminBundle\Entity\Post $post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get Post
     *
     * @return \Labs\AdminBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set Format
     *
     * @param \Labs\AdminBundle\Entity\Format $format
     *
     * @return Media
     */
    public function setFormat(\Labs\AdminBundle\Entity\Format $format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get Format
     *
     * @return \Labs\AdminBundle\Entity\Format
     */
    public function getFormat()
    {
        return $this->format;
    }
    

    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur
        return 'uploads/posts';
    }


    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }


    /**
     * @return string
     */
    public function getAssertPath()
    {
        return $this->getUploadDir().'/'.$this->url;
    }
    

    /**
     * @ORM\PostRemove()
     */
    public function deleteMedia()
    {
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->getAssertPath())) {
            // On supprime le fichier
            unlink($this->getAssertPath());
        }
    }
}
