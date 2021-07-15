<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface; //Uset ton encode the user password


use App\Entity\User; // Get access ton User Entity

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Loop to create 5 users
        for ($i=1; $i < 6; $i++) { 
            $user = new User();
            $user->setEmail("useremail" . $i . "@exemple.com");
            $password = $this->encoder->encodePassword($user, "password" . $i);
            $user->setPassword($password);

            $firstnameTypes = array("Michel", "Patrick", "Robert", "Lucien", "Monique", "Rigoberta", "Vivianne", "Suzette", "Glenda");
            $randomKey = array_rand($firstnameTypes, 1);
            $user->setFirstname($firstnameTypes[$randomKey]);

            $lastnameTypes = array("Blogodard", "Tripignek", "Debronchi", "Frapissant", "Treg", "Goldru", "Evelain", "Duchon", "Struppin");
            $randomKey = array_rand($lastnameTypes, 1);
            $user->setLastname($lastnameTypes[$randomKey]);

            //Setting a random selection in the 3 avalaible genders
            $genderTypes = array("Homme", "Femme");
            $randomKey = array_rand($genderTypes, 1);
            $user->setSex($genderTypes[$randomKey]);

            $user->setBirthdate(new \DateTime("04/07/1973"));

            //Génère un nombre aléatoire de comptes pour l'utilisateur
            // for ($j=1; $j < 3; $j++) { 
            //     $account = new Account();
            //     $account->setAmount(mt_rand(1, 99999));
            //     $account->setOpeningDate(new \DateTime());

            //     //Setting a random selection in the 3 avalaible bank accounts
            //     $accountTypes = array('Compte-courant', 'Livret-A', 'PEL');
            //     $randomKey = array_rand($accountTypes, 1);
            //     $account->setAccountType($accountTypes[$randomKey]);

            //     $account->setUser($user);

            //     //Génère des opérations pour chaque compte
            //     for ($k=1; $k < 4; $k++) { 
            //         $operation = new Operation();

            //         //Setting a random selection in the 2 avalaible operations types
            //         $operationTypes = array("crédit", "débit");
            //         $randomKey = array_rand($operationTypes, 1);
            //         $operation->setOperationType($operationTypes[$randomKey]);

            //         $operation->setOperationAmount(mt_rand(1, 400));

            //         $operation->setRegistered(new \DateTime());
            //         $operation->setLabel("Random label " . $k);
            //         $operation->setAccount($account);
            //         $manager->persist($operation);
            //     }
            //     $manager->persist($account);
            // }
            $manager->persist($user);
        }
        $manager->flush();
    }
}
