<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Customer;
use App\Factory\CustomerFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class CustomersTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    public function testGetCustomers(): void
    {
        CustomerFactory::createMany(100);

        static::createClient()->request('GET', '/customers');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@context' => '/contexts/Customer',
            '@type' => 'hydra:Collection'
        ]);
    }

    public function testGetCustomerById(): void
    {
        $testEmail = 'john.doe@email.com';
        CustomerFactory::createOne(['email' => $testEmail, 'password' => 'loremipsum']);
        $iri = $this->findIriBy(Customer::class, ['email' => $testEmail]);

        static::createClient()->request('GET', $iri);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@context' => '/contexts/Customer',
            '@type' => 'Customer',
            'email' => $testEmail
        ]);
    }

    public function testGetNonExistentCustomerById(): void
    {
        $testEmail = 'john.doe@email.com';
        CustomerFactory::createOne(['email' => $testEmail, 'password' => 'loremipsum']);

        static::createClient()->request('GET', 'customers/9999');

        $this->assertResponseStatusCodeSame(404);
    }
}
