<?php
/**
 * Created by PhpStorm.
 * User: raphael
 * Date: 21/04/2017
 * Time: 10:21
 */

namespace Labs\AdminBundle\Services;


use Doctrine\ORM\EntityManagerInterface;

class getImagePath
{


    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return mixed
     * return path domain directory
     */
    public function getImagesPathSys(){
        $options = $this->getSettingData();
        return $options->getImagePath();
    }

    /**
     * @return mixed
     * return domain directory setting name
     */
    public function getImagesParameterName(){
        $options = $this->getSettingData();
        return $options->getName();
    }

    /**
     * @return mixed
     * return directory domain name
     */
    public function getImagesDomainName(){
        $options = $this->getSettingData();
        return $options->getDomain(); 
    }
    
    private function getSettingData()
    {
        return $this->em->getRepository('LabsAdminBundle:Setting')->getSettingData();
    }

}