<?php

namespace  Labs\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Labs\AdminBundle\Entity\Post;
use Labs\AdminBundle\Entity\Item;
use Labs\AdminBundle\Entity\Media;

class LoadUserData implements FixtureInterface
{


    public function load(ObjectManager $manager)
    {
        $content = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.

Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.

Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.

Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.';

        $items = $manager->getRepository('LabsAdminBundle:Item')->findAll();
        $item_tab = [];
        foreach ($items as $item){
            $item_tab[] = $item;
        }
        $image = array("1.jpg", "2.jpg", "4.jpg");

        foreach ($item_tab as $k => $data){
            $media = new Media();
            $media->setUrl(array_rand($image,1));
            $media->setActived(1);
            for ($i = 1; $i < 4; $i++){
                $date = new \DateTime('now');
                $date->modify('+'.$k.'day');

                $post = new Post();
                $post->setName('Législatives 2016 : la CEI invite les candidats les résultats sur les réseaux sociaux avant la proclamation officielle');
                $post->setContent($content);
                $post->setDraft(1);
                $post->setOnline(1);
                $post->setCreated($date);
                $post->setItem($data);
                $post->addMedia($media);
                $manager->persist($post);
                $manager->flush();
            }
        }

    }
}