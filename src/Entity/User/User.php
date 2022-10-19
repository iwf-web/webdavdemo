<?php

namespace App\Entity\User;

use App\Entity\Traits\TimestampableAndBlamableTrait;
use App\Repository\User\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'app_user')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('username')]
#[UniqueEntity('email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableAndBlamableTrait;

    public const ROLE_DEFAULT = 'ROLE_USER';
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    #[ORM\Column, ORM\Id, ORM\GeneratedValue]
    protected ?int $id = null;

    #[ORM\Column(length: 25, unique: true)]
    protected string $username;

    #[ORM\Column(length: 64)]
    protected string $password;

    #[ORM\Column(length: 60, unique: true)]
    protected string $email;

    #[ORM\Column(length: 2)]
    protected string $language = 'de';

    #[ORM\Column(nullable: true)]
    protected ?string $firstName = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Length(min: 2)]
    protected ?string $lastName = null;


    #[ORM\Column(type: "simple_array", nullable: true)]
    protected array $roles;


    public function __construct()
    {
        $this->roles = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getSalt(): ?string
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function eraseCredentials(): void
    {
    }

    /** @see \Serializable::serialize() */
    public function __serialize(): array
    {
        return [
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ];
    }

    public function __unserialize(array $serialized): void
    {
        [$this->id, $this->username, $this->password,] = $serialized;
    }

    public function setFirstName(?string $firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setLastName(?string $lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getLanguageTranslationKey(): string
    {
        return 'lang.' . $this->language;
    }

    public function setLanguage(string $language): User
    {
        $this->language = $language;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        $allRoles = $this->roles;

        return array_unique($allRoles);
    }

    public function setRoles(array $roles): User
    {
        $this->roles = $roles;

        return $this;
    }

    public function hasRole(string $role): bool
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }


    /**
     * @return array Returns array with all specific roles from user (Roles in User Groups are not added!)
     */
    public function getRolesFromUser(): array
    {
        return $this->roles;
    }

    public function getFullName(): string
    {
        return ($this->getLastName() && $this->getFirstName()) ? $this->getFirstName() . ' ' . $this->getLastName() : $this->getUsername();
    }
}
