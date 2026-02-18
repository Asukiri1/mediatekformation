<?php

namespace App\Tests\Integration;

use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationValidationsTest extends KernelTestCase
{
    /**
     * Test la date de demain et doit renvoyer une erreur
     * @return void
     */
    public function testDatePosteriorToTodayIsInvalid(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $validator = $container->get('validator');

        $formation = new Formation();
        $formation->setTitle("Test Future Date");
        $formation->setPublishedAt(new \DateTime('+1 day'));
        
        $formation->setPlaylist(new Playlist());

        $errors = $validator->validate($formation);

        $this->assertCount(1, $errors, "La validation aurait dû échouer pour une date future.");
    }
    
    /**
     * Test la date de hier et doit renvoyer un succés 
     * @return void
     */
    public function testDatePriorToTodayIsValid(): void
    {
        self::bootKernel();
        $validator = static::getContainer()->get('validator');

        $formation = new Formation();
        $formation->setTitle("Test Past Date");
        $formation->setPublishedAt(new \DateTime('-1 day'));
        $formation->setPlaylist(new Playlist());

        $errors = $validator->validate($formation);

        $this->assertCount(0, $errors, "La validation aurait dû réussir pour une date passée.");
    }
}