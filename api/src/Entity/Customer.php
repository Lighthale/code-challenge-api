<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\CustomerRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: [
                'groups' => ['get']
            ]
        ),
        new GetCollection(
            normalizationContext: [
                'groups' => ['getCollection']
            ]
        ),
        new Delete() // To enable delete on admin
    ],
)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    #[Groups('get')]
    private ?int $id = null;

    #[Groups(['get', 'getCollection'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fullName = null;

    #[Groups(['get', 'getCollection'])]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[Groups('get')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $username = null;

    #[Groups('get')]
    #[ORM\Column(length: 6, nullable: true)]
    private ?string $gender = null;

    #[Groups(['get', 'getCollection'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;

    #[Groups('get')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[Groups('get')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
}
