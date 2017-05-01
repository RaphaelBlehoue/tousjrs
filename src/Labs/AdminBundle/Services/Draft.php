<?php

namespace Labs\AdminBundle\Services;

use Doctrine\ORM\EntityManager;

class Draft
{
    
    private $em;
    
    public function __construct(EntityManager $entityManager)
    {
       $this->em = $entityManager; 
    }

    /**
     * @param $user
     * @param $entity
     * @return mixed
     * @throws \Exception
     */
    public function DraftCreate($user, $entity)
    {
        $entityName = $this->getEntityClassName($entity);
        $draft = $this->em->getRepository($entityName)->getDraftUser($user);
        if(null !== $draft){
            return $draft;
        }else{
            return $this->persistDraft($user, $entityName);
        }
    }

    /**
     * @param $user
     * @param $entityName
     * @return mixed
     * @throws \Exception
     */
    private function persistDraft($user, $entityName)
    {
        $draft = new $entityName;
        $draft->setDraft(-1);
        $draft->setOnline(0);
        $draft->setUser($user);
        $this->em->persist($draft);
        $this->em->flush();
        return $draft;
    }

    /**
     * @param $entity
     * @return string
     */
    private function getEntityClassName($entity)
    {
        return $entity_class = $this->em->getClassMetadata(get_class($entity))->getName();
    }
    
}