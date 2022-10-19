<?php
/**
 * AppUserProvider.php
 *
 * @author nicofurrer / IWF AG / Web Solutions
 * @since  03/2017
 */

namespace App\Service\Security\User;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AppUserProvider implements UserProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * AppUserProvider constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(User::class);
    }

    /**
     * @param int $id
     * @return null|User|object
     */
    public function findUser(int $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param string $username
     * @return null|User
     */
    public function findUserByUsername(string $username)
    {
        return $this->repository->findOneBy(['username' => $username]);
    }

    /**
     * @param $username
     * @return null|User
     */
    public function findUserByUsernameOrEmail(string $username)
    {
        return $this->repository->findUserByUsernameOrEmail($username);
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        return $this->loadUserByIdentifier($username);
    }

    /**
     * Loads the user for the given username.
     *
     * This method must return null if the user is not found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->findUserByUsernameOrEmail($identifier);

        if (!$user instanceof UserInterface) {
            $e = new UserNotFoundException();
            $e->setUserIdentifier($identifier);
            throw $e;
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user): UserInterface
    {

        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Expected an instance of %s, but got "%s".', User::class, get_class($user)));
        }

        if (null === $reloadedUser = $this->findUserByUsername($user->getUsername())) {

            $e = new UserNotFoundException(sprintf('User with Username "%s" could not be reloaded.', $user->getUsername()));
            $e->setUserIdentifier($user->getUsername());
        }

        return $reloadedUser;
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class): bool
    {
        return User::class === $class;
    }
}
