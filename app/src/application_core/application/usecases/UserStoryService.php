<?php

namespace jira\application_core\application\usecases;

use jira\application_core\domain\entities\UserStory;
use jira\application_core\domain\entities\UserStoryStatus;
use jira\application_core\domain\exceptions\StatusChangeNotAllowedException;
use jira\application_core\domain\exceptions\UserStoryNotFoundException;
use jira\application_core\application\ports\spi\UserStoryRepository;
use jira\application_core\application\ports\api\UserStoryServiceInterface;
use Ramsey\Uuid\Uuid;

class UserStoryService implements UserStoryServiceInterface {

    private UserStoryRepository $userStoryRepository;

    public function __construct(UserStoryRepository $userStoryRepository) {
        $this->userStoryRepository = $userStoryRepository;
    }

    /**
     * @return UserStory[]
     */
    public function getAllUserStories(): array {
        return $this->userStoryRepository->findAll();
    }

    /**
     * @throws UserStoryNotFoundException
     * @throws StatusChangeNotAllowedException
     */
    public function changeStatus(string $id, UserStoryStatus $newStatus): void {
        $userStory = $this->userStoryRepository->findById($id);
        $userStory->changeStatus($newStatus);
        $this->userStoryRepository->save($userStory);
    }
}