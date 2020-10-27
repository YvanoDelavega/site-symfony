<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
//use Cocur\Slugify\Slugify;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

/**
 * php bin/console make:fixtures
 * 
 * php bin/console doctrine:fixtures:load
 *
 * @param ObjectManager $manager
 * @return void
 */
    public function load(ObjectManager $manager)
    {
        $fake = Factory::create('FR-fr');
        //  $slugify = new Slugify();

        //gestion des utiliseurs
        $users = [];
        $genres = ['male', 'female'];

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $genre = $fake->randomElement($genres);
            $picture = "https://randomuser.me/api/portraits/";
            $pictureId = $fake->numberBetween(1, 99) . '.jpg';

            $picture .= ($genre == "male" ? "men/" : "women/") . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($fake->firstname($genre))
                ->setLastName($fake->lastname)
                ->setEmail($fake->email)
                ->setIntroduction($fake->sentence())
                ->setDescription('<p>' . join('</p><p>', $fake->paragraphs(3)) . '</p>')
                ->setHash($hash)
                ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user; // ajoute le user courant au tableau
        }

        //gestion des annonces
        for ($i = 0; $i <= 30; $i++) {

            $ad = new Ad();

            $title = $fake->sentence();
            // $slug = $slugify->slugify($title);
            $codeImage  = "http://loremflickr.com/300/200?random$i"; //$fake->imageUrl(200, 100);
            $introduction = $fake->paragraph(2);
            $content = '<p>' . join('</p><p>', $fake->paragraphs(5)) . '</p>';

            $user = $users[mt_rand(0, count($users) - 1)];

            $ad->setTitle($title)
                //    ->setSlug($slug)
                ->setCoverImage($codeImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user);


            for ($j = 1; $j < mt_rand(2, 5); $j++) {
                $image = new Image();
                //  $image->setUrl($fake->imageUrl())
                $image->setUrl("http://loremflickr.com/300/200?random" . mt_rand(1, 10000))
                    ->setCaption($fake->sentence())
                    ->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
        }
        $manager->flush();
    }
}
