<?php
/**
 * AppUserManager.php
 *
 * @author nicofurrer / IWF AG / Web Solutions
 * @since  03/2017
 */

namespace App\Service\User\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Coala\DateProviderBundle\Service\DateProvider\DateProvider;
use Coala\DateProviderBundle\Service\DateProvider\DateProviderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppUserManager
{
    protected UserRepository $repository;

    protected UserPasswordHasherInterface $passwordEncoder;

    private DateProvider $dateProvider;

    /**
     * AppUserManager constructor.
     */
    public function __construct(
        UserRepository              $userRepository,
        UserPasswordHasherInterface $passwordEncoder,
        DateProviderInterface       $dateProvider
    )
    {
        $this->repository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->dateProvider = $dateProvider;
    }

    public function findUser(int $id): ?User
    {
        return $this->repository->find($id);
    }

    public function findUserByUsername(string $username): ?User
    {
        return $this->repository->findOneBy(['username' => $username]);
    }

    public function findUserByUsernameOrEmail(string $username): ?User
    {
        return $this->repository->findUserByUsernameOrEmail($username);
    }

    public function findUserByConfirmationToken(string $token)
    {
        return $this->repository->findOneBy(['confirmationToken' => $token]);
    }

    public function setPlainPassword(User $user, string $newPassword): void
    {
        $user->setPassword($this->passwordEncoder->hashPassword($user, $newPassword));
    }

    public function updatePasswordRequested(User $user, $confirmationToken): void
    {
        $user->setPasswordRequested($confirmationToken, $this->dateProvider->getCurrentDate());
        $this->repository->save($user);
    }

    public function updateLastLogin(User $user, $lastLogin = null)
    {
        if (!$lastLogin) {
            $lastLogin = $this->dateProvider->getCurrentDate();
        }

        $user->setLastLogin($lastLogin);

        $this->repository->save($user);
    }
}
