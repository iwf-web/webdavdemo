<?php
/**
 * UserActionChecker.php
 *
 * @author nicofurrer / IWF AG / Web Solutions
 * @since  02/2019
 */

namespace App\Service\User\User;

use App\Entity\User\User;
use Coala\PermissionsBundle\Service\FrontendActionManager\ActionCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserActionChecker implements ActionCheckerInterface
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * UserActionChecker constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param User $entity
     * @param string $action
     * @return bool
     */
    public function can($entity, string $action): bool
    {
        switch ($action) {
            // a user cannot deactivate nor delete himself
            case 'deactivate':
                $can = $this->getLoggedInUser()->getId() !== $entity->getId() && $entity->isEnabled();
                break;
            case 'activate':
                $can = !$entity->isEnabled();
                break;
            case 'delete':
                $can = $this->getLoggedInUser()->getId() !== $entity->getId();
                break;
            default:
                $can = true;
        }

        return $can;
    }

    protected function getLoggedInUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}
