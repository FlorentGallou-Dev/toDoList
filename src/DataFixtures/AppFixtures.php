<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface; //Used to encode the user password


use App\Entity\User; // Get access to User Entity
use App\Entity\Project; // Get access to Project Entity
use App\Entity\Task; // Get access to Task Entity

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

            //Loop to create randomly between 1 to 4 projects for the actualy created User
            for ($j=1; $j < 5; $j++) { 
                $project = new Project();

                //Setting a random selection in the 5 project subjects
                $subjectTypes = array('Réfection de la façade nord chez Robert Bruvant', 'Nouvelle terrasse PVC chez Gizelle Ystral', 'SAV sur papiers peint chez Chris McNeil', 'Nouvelles fenêtres double vitrage chez la Diva Plavalaguna', 'SAV sur les WC chez Marry Swanson');
                $randomKey = array_rand($subjectTypes, 1);
                $project->setSubject($subjectTypes[$randomKey]);

                $project->setDescription("Lorem Ipsum is simply dummy text of the printing and typesetting industry.");

                $project->setCreationDate(new \DateTime());
                $project->setDeadlineDate(new \DateTime("2042-12-14"));

                $project->setStatus(mt_rand(0, 1)); // Sets randomly status to 0 or 1 as boolean

                $project->setUser($user);

                 //Stting  a random amount of tasks between 1 to 10 for the actualy created Project
                for ($k=1; $k < 11; $k++) { 
                    $task = new Task();

                    //Setting a random selection in the 5 task titles
                    $taskTypes = array('Prendre bougies', 'Recharger bouteille d\'eau bénite', 'Commander du parquet', 'Vérifier les fondations', 'Assurer l\'opérateur avec un mousqueton', 'Appeler un ami', 'Prendre la caisse rouge', 'Réparer le trou dans le mur', 'Garder un oeil sur le niveau du jaune', 'Demander une augmentation', 'Prétendre à une maladie si Patrick demande un remplacement', 'Créer un cadre en bois', 'Acheter 14 solives de 2m12');
                    $randomKey = array_rand($taskTypes, 1);
                    $task->setTitle($taskTypes[$randomKey]);

                    $task->setDescription("Lorem Ipsum is simply dummy text of the printing and typesetting industry.");

                    $task->setCreationDate(new \DateTime());
                    $task->setDeadlineDate(new \DateTime("2042-12-14"));

                    $task->setStatus(mt_rand(0, 1)); // Sets randomly status to 0 or 1 as boolean

                    $task->setProject($project);

                    $manager->persist($task);
                }
                 $manager->persist($project);
             }
            $manager->persist($user);
        }
        $manager->flush();
    }
}
