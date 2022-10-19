<?php
/**
 * CurrentUserProvider.php
 *
 * @author Michael Bogucki / IWF AG / Web Solutions
 * @since  05/2019
 */

namespace App\Service\User\User;

use App\Entity\User\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CurrentUserProvider
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return User|null
     */
    public function getLoggedInUser()
    {
        $token = $this->tokenStorage->getToken();
        if ($token && $token instanceof TokenInterface && $token->getUser() instanceof User) {
            return $token->getUser();
        }

        return null;
    }

}