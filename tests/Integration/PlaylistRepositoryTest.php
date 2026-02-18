<?php

namespace App\Tests\Integration;

use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaylistRepositoryTest extends KernelTestCase
{
    /**
     * Test l'envoi et le tri des objets
     * @return void
     */
    public function testFindAllOrderByNbFormations(): void
    {
        self::bootKernel();
        $repository = static::getContainer()->get(PlaylistRepository::class);

        $playlists = $repository->findAllOrderByNbFormations('ASC');

        $this->assertIsArray($playlists);
        
        if (count($playlists) >= 2) {
            $first = $playlists[0]->getFormations()->count();
            $last = $playlists[count($playlists) - 1]->getFormations()->count();
            $this->assertLessThanOrEqual($last, $first);
        }
    }
}