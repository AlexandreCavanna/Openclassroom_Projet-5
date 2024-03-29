<?php

namespace App\DataFixtures;

use App\Entity\Candidature;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Offer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $roles;
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        $studentRole = new Role();

        $studentRole->setTitle('ROLE_STUDENT');
        $manager->persist($studentRole);

        $this->roles[] = $studentRole;

        $employerRole = new Role();

        $employerRole->setTitle('ROLE_EMPLOYER');
        $manager->persist($employerRole);

        $this->roles[] = $employerRole;



        // $adminUser = new User();
        // $adminUser->setFirstname('Alexandre')
        // ->setLastname('Cavanna')
        // ->setEmail('alexandre.cavanna.pro@gmail.com')
        // ->setIntroduction($faker->sentence())
        // ->setDescription('<p>'. join('<p></p>', $faker->paragraphs(3)) . '<p>')
        // ->setHash($this->encoder->encodePassword($adminUser, 'password'))
        // ->setPicture('https://avatars.io/twitter/ispirkcsgo')
        // ->addUserRole($adminRole);

        // $manager->persist($adminUser);

        $users = [];
        $genres = ['male', 'female'];

        // Handle / manage users

        for ($i = 0; $i <= 10; $i++) {
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $role = $this->roles[array_rand($this->roles)];

            $user->setFirstname($faker->firstName($genre))
                ->setLastname($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction('<p>' . join('</p><p>', $faker->paragraphs(1)) . '</p>')
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setHash($hash)
                ->setPicture($picture)
                ->addUserRole($role);

            $manager->persist($user);
            $users[] = $user;
        }


        // Handle / manage Job Offers
        for ($i = 0; $i <= 30; $i++) {
            $offer = new Offer();

            $title = $faker->sentence(2);
            $description = $faker->paragraph(6);
          
            $user = $users[mt_rand(0, count($users) - 1)];

            $offer->setTitle($title)
                ->setDescription($description)
                ->setAuthor($user);

            // Handle / manage Job candidature
            for ($j = 1; $j <= mt_rand(0, 10); $j++) {
                $candidature = new Candidature();

                $student = $users[mt_rand(0, count($users) - 1)];
                $candidature->setStudent($student)
                    ->setOffer($offer)
                    ->setcoverLetter($faker->paragraph(6))
                    ->setcvFileName($faker->file('public/cv_fixture', 'public/uploads/cv', false));

                $manager->persist($candidature);
            }

            $manager->persist($offer);
        }
        $manager->flush();
    }
}
