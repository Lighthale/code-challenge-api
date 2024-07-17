<?php

namespace App\Factory;

use App\Entity\Customer;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class CustomerFactory extends PersistentProxyObjectFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function class(): string
    {
        return Customer::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'email' => self::faker()->email(),
            'password' => self::faker()->text(10),
            'fullName' => self::faker()->name('male'),
            'username' => self::faker()->userName(),
            'gender' => 'male',
            'country' => 'Australia',
            'city' => self::faker()->city(),
            'phone' => self::faker()->phoneNumber()
        ];
    }

    protected function initialize(): static
    {
        return $this;
    }
}
