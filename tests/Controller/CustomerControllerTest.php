<?php

namespace App\Tests\Controller;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CustomerControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $customerRepository;
    private string $path = '/customer/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->customerRepository = $this->manager->getRepository(Customer::class);

        foreach ($this->customerRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Клиенты');
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'customer[name]' => 'TestName',
            'customer[surname]' => 'TestSurname',
            'customer[phone]' => '+7(901)999-99-99',
            'customer[email]' => 'testMail@gmail.com',
            'customer[Education]' => '0',
            'customer[pdProcessingPermission]' => '1',
        ]);

        self::assertSame(1, $this->customerRepository->count([]));
    }

    public function testShow(): void
    {
        $fixture = new Customer();
        $fixture->setName('TestName');
        $fixture->setSurname('TestSurname');
        $fixture->setPhone('+7(901)999-99-99');
        $fixture->setEmail('test@Mailgmail.com');
        $fixture->setEducation(Customer\EducationType::HIGHER);
        $fixture->setPdProcessingPermission(true);
        $fixture->setScore(32);

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
    }

    public function testEdit(): void
    {
        $fixture = new Customer();
        $fixture->setName('TestName');
        $fixture->setSurname('TestSurname');
        $fixture->setPhone('+7(901)999-99-99');
        $fixture->setEmail('testMail@gmail.com');
        $fixture->setEducation(Customer\EducationType::HIGHER);
        $fixture->setPdProcessingPermission(false);
        $fixture->setScore(32);

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'customer[name]' => 'NewName',
            'customer[surname]' => 'NewSurname',
            'customer[phone]' => '+7(902)999-99-99',
            'customer[email]' => 'newMail@mail.ru',
            'customer[Education]' => '0',
            'customer[pdProcessingPermission]' => '1',
        ]);

        $fixture = $this->customerRepository->find($fixture->getId());

        self::assertSame('NewName', $fixture->getName());
        self::assertSame('NewSurname', $fixture->getSurname());
        self::assertSame('+7(902)999-99-99', $fixture->getPhone());
        self::assertSame('newMail@mail.ru', $fixture->getEmail());
        self::assertSame('Высшее образование', $fixture->getEducation());
        self::assertSame(true, $fixture->pdProcessingPermission());
    }

    public function testRemove(): void
    {
        $fixture = new Customer();
        $fixture->setName('TestName');
        $fixture->setSurname('TestSurname');
        $fixture->setPhone('+7(901)999-99-99');
        $fixture->setEmail('testMail@gmail.com');
        $fixture->setEducation(Customer\EducationType::HIGHER);
        $fixture->setPdProcessingPermission(true);
        $fixture->setScore(32);

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame(null, $this->customerRepository->find($fixture->getId()));
    }
}
