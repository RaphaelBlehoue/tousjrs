<?php

namespace Labs\AdminBundle\Services;

use Doctrine\ORM\EntityManager;
use Labs\AdminBundle\Entity\Format;
use Labs\AdminBundle\Entity\Post;

class Draft
{
    
    private $em;
    
    public function __construct(EntityManager $entityManager)
    {
       $this->em = $entityManager; 
    }

    /**
     * @param $user
     * @return Post|mixed
     */
    public function DraftCreate($user)
    {
        $draft = $this->em->getRepository('LabsAdminBundle:Post')->getDraftUser($user);
        if(null === $draft){
            $post = new Post();
            $post->setDraft(0);
            $post->setOnline(0);
            $post->setUser($user);
            $this->em->persist($post);
            $this->em->flush();
            $draft = $post;
            return $draft;
        }else{
            return $draft;
        }
    }

    /**
     * @param $user
     * @return Format|mixed
     */
    public function DraftFormatCreate($user)
    {
        $draft = $this->em->getRepository('LabsAdminBundle:Format')->getDraftUser($user);
        if(null === $draft){
            $format = new Format();
            $format->setDraft(0);
            $format->setOnline(0);
            $format->setUser($user);
            $this->em->persist($format);
            $this->em->flush();
            $draft = $format;
            return $draft;
        }else{
            return $draft;
        }
    }

}