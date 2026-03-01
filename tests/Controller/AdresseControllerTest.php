<?php

namespace App\Tests\Controller;

use App\Entity\Adresse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AdresseControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $adresseRepository;
    private string $path = '/adresse/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->adresseRepository = $this->manager->getRepository(Adresse::class);

        foreach ($this->adresseRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Adresse index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'adresse[adresse]' => 'Testing',
            'adresse[cp]' => 'Testing',
            'adresse[ville]' => 'Testing',
            'adresse[user]' => 'Testing',
        ]);

        self::assertResponseRedirects('/adresse');

        self::assertSame(1, $this->adresseRepository->count([]));

        $this->markTestIncomplete('This test was generated');
    }

    public function testShow(): void
    {
        $fixture = new Adresse();
        $fixture->setAdresse('My Title');
        $fixture->setCp('My Title');
        $fixture->setVille('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Adresse');

        // Use assertions to check that the properties are properly displayed.
        $this->markTestIncomplete('This test was generated');
    }

    public function testEdit(): void
    {
        $fixture = new Adresse();
        $fixture->setAdresse('Value');
        $fixture->setCp('Value');
        $fixture->setVille('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'adresse[adresse]' => 'Something New',
            'adresse[cp]' => 'Something New',
            'adresse[ville]' => 'Something New',
            'adresse[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/adresse');

        $fixture = $this->adresseRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getAdresse());
        self::assertSame('Something New', $fixture[0]->getCp());
        self::assertSame('Something New', $fixture[0]->getVille());
        self::assertSame('Something New', $fixture[0]->getUser());

        $this->markTestIncomplete('This test was generated');
    }

    public function testRemove(): void
    {
        $fixture = new Adresse();
        $fixture->setAdresse('Value');
        $fixture->setCp('Value');
        $fixture->setVille('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/adresse');
        self::assertSame(0, $this->adresseRepository->count([]));

        $this->markTestIncomplete('This test was generated');
    }
}
