<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
//use Cocur\Slugify\Slugify;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $fake = Factory::create('FR-fr');
        //  $slugify = new Slugify();

        for ($i = 0; $i <= 30; $i++) {

            $ad = new Ad();

            $title = $fake->sentence();
            // $slug = $slugify->slugify($title);
            $codeImage  = "http://loremflickr.com/300/200?random$i";//$fake->imageUrl(200, 100);
            $introduction = $fake->paragraph(2);
            $content = 'p' . join('</p><p>', $fake->paragraphs(5)) . '</p>';


            $ad->setTitle($title)
                //    ->setSlug($slug)
                ->setCoverImage($codeImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5));


            for ($j = 1; $j < mt_rand(2, 5); $j++) {
                $image = new Image();
              //  $image->setUrl($fake->imageUrl())
              $image->setUrl("http://loremflickr.com/300/200?random". mt_rand(1, 10000))
                    ->setCaption($fake->sentence())
                    ->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
        }
        $manager->flush();
    }
}
