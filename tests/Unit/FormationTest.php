<?php

namespace App\Tests\Unit;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

class FormationTest extends TestCase
{
    /**
     * Vérifie que la date s'affiche dans le bon format
     * @return void
     */
    public function testGetPublishedAtString(): void
    {
        $formation = new Formation();
        $date = new \DateTime("2023-01-04 17:00:12");
        $formation->setPublishedAt($date);

        $this->assertEquals("04/01/2023", $formation->getPublishedAtString());
    }
}