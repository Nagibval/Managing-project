<?php

namespace App\Test\Controller;

use App\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddressControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/address/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Address::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Address index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'address[title]' => 'Testing',
            'address[fname]' => 'Testing',
            'address[lname]' => 'Testing',
            'address[address]' => 'Testing',
            'address[city]' => 'Testing',
            'address[cp]' => 'Testing',
            'address[country]' => 'Testing',
            'address[user]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Address();
        $fixture->setTitle('My Title');
        $fixture->setFname('My Title');
        $fixture->setLname('My Title');
        $fixture->setAddress('My Title');
        $fixture->setCity('My Title');
        $fixture->setCp('My Title');
        $fixture->setCountry('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Address');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Address();
        $fixture->setTitle('Value');
        $fixture->setFname('Value');
        $fixture->setLname('Value');
        $fixture->setAddress('Value');
        $fixture->setCity('Value');
        $fixture->setCp('Value');
        $fixture->setCountry('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'address[title]' => 'Something New',
            'address[fname]' => 'Something New',
            'address[lname]' => 'Something New',
            'address[address]' => 'Something New',
            'address[city]' => 'Something New',
            'address[cp]' => 'Something New',
            'address[country]' => 'Something New',
            'address[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/address/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getFname());
        self::assertSame('Something New', $fixture[0]->getLname());
        self::assertSame('Something New', $fixture[0]->getAddress());
        self::assertSame('Something New', $fixture[0]->getCity());
        self::assertSame('Something New', $fixture[0]->getCp());
        self::assertSame('Something New', $fixture[0]->getCountry());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Address();
        $fixture->setTitle('Value');
        $fixture->setFname('Value');
        $fixture->setLname('Value');
        $fixture->setAddress('Value');
        $fixture->setCity('Value');
        $fixture->setCp('Value');
        $fixture->setCountry('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/address/');
        self::assertSame(0, $this->repository->count([]));
    }
}
