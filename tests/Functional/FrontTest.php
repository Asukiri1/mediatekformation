<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontTest extends WebTestCase
{
    /**
     * Teste l'accès à la page d'accueil et le lien vers les formations
     * @return void
     */
    public function testPageAccueilAndLink(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Accueil');

        $link = $crawler->selectLink('Formations')->link();
        $client->click($link);
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('formations');
    }

    /**
     * Teste le tri des formations (Page Formations)
     * @return void
     */
    public function testSortFormations(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/title/ASC');
        
        $this->assertResponseIsSuccessful();
        
        $this->assertSelectorExists('table tbody tr');
    }

    /**
     * Teste le filtre de recherche des formations (Page Formations)
     * @return void
     */
    public function testFilterFormations(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');

        $form = $crawler->selectButton('filtrer')->form([
            'recherche' => 'Java'
        ]);

        $client->submit($form);
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('table tbody tr', 'Java');
    }

    /**
     * Teste le tri des Playlists (Page Playlists)
     * @return void
     */
    public function testSortPlaylists(): void
    {
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/name/ASC');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table tbody tr');
    }

    /**
     * Teste le clic sur une playlist pour voir le détail
     * @return void
     */
    public function testClickPlaylistDetail(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');

        $link = $crawler->selectLink('Voir détail')->link();
        $client->click($link);

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('playlists.showone');
        $this->assertSelectorExists('h4');
    }
}