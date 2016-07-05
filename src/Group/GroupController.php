<?php

namespace Sinergi\Users\Group;

use Interop\Container\ContainerInterface;
use Sinergi\Users\Container;
use Sinergi\Users\Group\Exception\InvalidGroupException;
use Sinergi\Users\User\UserEntityInterface;
use Sinergi\Users\User\UserRepositoryInterface;
use Sinergi\Users\User\UserValidatorInterface;

class GroupController
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        if ($container instanceof Container) {
            $this->container = $container;
        } else {
            $this->container = new Container($container);
        }
    }

    public function createGroup(GroupEntityInterface $group, array $users = null): GroupEntityInterface
    {
        $userErrors = [];
        $hasErrors = false;
        if (is_array($users) && count($users)) {
            /** @var UserValidatorInterface $userValidator */
            $userValidator = $this->container->get(UserValidatorInterface::class);
            foreach ($users as $user) {
                $userErrors[] = $userValidator($user);
                if (count(end($userErrors))) {
                    $hasErrors = true;
                }
            }
        }

        /** @var GroupValidatorInterface $groupValidator */
        $groupValidator = $this->container->get(GroupValidatorInterface::class);
        $errors = $groupValidator($group);

        if (count($errors)) {
            $hasErrors = true;
        }

        if ($hasErrors) {
            throw new InvalidGroupException(array_merge($errors, ['users' => $userErrors]));
        }

        /** @var GroupRepositoryInterface $groupRepository */
        $groupRepository = $this->container->get(GroupRepositoryInterface::class);
        $groupRepository->save($group);

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->container->get(UserRepositoryInterface::class);

        if (is_array($users) && count($users)) {

            /** @var UserEntityInterface $user */
            foreach ($users as $user) {
                $user->setGroupId($group->getId());
                $userRepository->save($user);
            }
        }

        $group->setUserRepository($userRepository);
        $group->getUsers();
        return $group;
    }
}
