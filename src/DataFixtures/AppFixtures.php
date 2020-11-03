<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use Faker\Factory;
//use Cocur\Slugify\Slugify;
use App\Entity\Role;
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
        $fake = Factory::create('fr_FR');
        //  $slugify = new Slugify();

        // gestion des roles
        $adminRole = new Role();
        $adminRole->setTitle("ROLE_ADMIN");
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName("Yvan")
            ->setLastName("GILLES")
            ->setEmail("admin@admin.fr")
            ->setHash($this->encoder->encodePassword($adminUser, 'password'))
            ->setPicture("https://randomuser.me/api/portraits/lego/5.jpg")
            ->setIntroduction($fake->sentence())
            ->setDescription('<p>' . join('</p><p>', $fake->paragraphs(3)) . '</p>')
            ->addUseRole($adminRole);
        $manager->persist($adminUser);

        //gestion des utilisateurs
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
            $codeImage  = "http://loremflickr.com/300/200/appartment?random" . mt_rand(1, 10000); //$fake->imageUrl(200, 100);
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
                $image->setUrl("http://loremflickr.com/300/200/appartment?random" . mt_rand(1, 10000))
                    ->setCaption($fake->sentence())
                    ->setAd($ad);
                $manager->persist($image);
            }

            // les réservations
            for ($j = 1; $j < mt_rand(0, 10); $j++) {

                $createdAt = $fake->dateTimeBetween('-6 months');
                $startDate = $fake->dateTimeBetween('-3 months');
                $comment = $fake->paragraph();
                $duration = mt_rand(3, 10);
                // ici on doit cloner sinon modify va modifier startDate et on aura startDate === endDate
                //$endDate = $startDate->modify("+$duration days");
                $endDate = (clone $startDate)->modify("+$duration days");
                $amount = $ad->getPrice() * $duration;
                $booker = $users[mt_rand(0, count($users) - 1)];
                $booking = new Booking();
                $booking->setBooker($booker)
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setAmount($amount)
                    ->setCreatedAt($createdAt)
                    ->setComment($comment)
                    ->setAd($ad);

                $manager->persist($booking);

                // gestion des commentaires
                if (mt_rand(0,1)){
                    $comment = new Comment();
                    $comment->setContent($fake->paragraph())
                    ->setRating(mt_rand(1,5))
                    ->setAuthor($booker)
                    ->setAd($ad);
                    $manager->persist($comment);
                }
            }

            $manager->persist($ad);
        }
        $manager->flush();
    }
}
