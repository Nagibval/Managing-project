<?php

namespace App\Test\Controller;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/project/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Project::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Project index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'project[libelle]' => 'Testing',
            'project[code]' => 'Testing',
            'project[description]' => 'Testing',
            'project[beginDate]' => 'Testing',
            'project[endDate]' => 'Testing',
            'project[estimateEndDate]' => 'Testing',
            'project[isFinished]' => 'Testing',
            'project[priority]' => 'Testing',
            'project[cost]' => 'Testing',
            'project[isSuccess]' => 'Testing',
            'project[user]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Project();
        $fixture->setLibelle('My Title');
        $fixture->setCode('My Title');
        $fixture->setDescription('My Title');
        $fixture->setBeginDate('My Title');
        $fixture->setEndDate('My Title');
        $fixture->setEstimateEndDate('My Title');
        $fixture->setIsFinished('My Title');
        $fixture->setPriority('My Title');
        $fixture->setCost('My Title');
        $fixture->setIsSuccess('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Project');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Project();
        $fixture->setLibelle('Value');
        $fixture->setCode('Value');
        $fixture->setDescription('Value');
        $fixture->setBeginDate('Value');
        $fixture->setEndDate('Value');
        $fixture->setEstimateEndDate('Value');
        $fixture->setIsFinished('Value');
        $fixture->setPriority('Value');
        $fixture->setCost('Value');
        $fixture->setIsSuccess('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'project[libelle]' => 'Something New',
            'project[code]' => 'Something New',
            'project[description]' => 'Something New',
            'project[beginDate]' => 'Something New',
            'project[endDate]' => 'Something New',
            'project[estimateEndDate]' => 'Something New',
            'project[isFinished]' => 'Something New',
            'project[priority]' => 'Something New',
            'project[cost]' => 'Something New',
            'project[isSuccess]' => 'Something New',
            'project[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/project/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getLibelle());
        self::assertSame('Something New', $fixture[0]->getCode());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getBeginDate());
        self::assertSame('Something New', $fixture[0]->getEndDate());
        self::assertSame('Something New', $fixture[0]->getEstimateEndDate());
        self::assertSame('Something New', $fixture[0]->getIsFinished());
        self::assertSame('Something New', $fixture[0]->getPriority());
        self::assertSame('Something New', $fixture[0]->getCost());
        self::assertSame('Something New', $fixture[0]->getIsSuccess());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Project();
        $fixture->setLibelle('Value');
        $fixture->setCode('Value');
        $fixture->setDescription('Value');
        $fixture->setBeginDate('Value');
        $fixture->setEndDate('Value');
        $fixture->setEstimateEndDate('Value');
        $fixture->setIsFinished('Value');
        $fixture->setPriority('Value');
        $fixture->setCost('Value');
        $fixture->setIsSuccess('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/project/');
        self::assertSame(0, $this->repository->count([]));
    }
}
