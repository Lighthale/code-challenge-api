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
        $response = static::createClient()->request('GET', '/customers');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertNotEmpty($response->getContent());
        $this->assertJsonContains([
            '@context' => '/contexts/Customer',
            '@type' => 'hydra:Collection'
        ]);
        $this->assertMatchesResourceCollectionJsonSchema(Customer::class);
    }

    public function testGetCustomerById(): void
    {
        $testEmail = 'john.doe@email.com';
        CustomerFactory::createOne(['email' => $testEmail, 'password' => 'loremipsum']);
        $iri = $this->findIriBy(Customer::class, ['email' => $testEmail]);
        $response = static::createClient()->request('GET', $iri);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertNotEmpty($response->getContent());
        $this->assertJsonContains([
            '@context' => '/contexts/Customer',
            '@type' => 'Customer',
            'email' => $testEmail
        ]);
        $this->assertMatchesResourceItemJsonSchema(Customer::class);
    }

    public function testGetNonExistentCustomerById(): void
    {
        CustomerFactory::createMany(100);
        static::createClient()->request('GET', 'customers/0');
        $this->assertResponseStatusCodeSame(404);
        $this->assertJsonContains(['detail' => 'Not Found']);
    }
}
