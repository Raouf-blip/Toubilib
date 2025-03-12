<?php

namespace jira\domain\usecases;

use jira\domain\StatusChangeNotAllowedException;
use jira\domain\UserStoryNotFoundException;
use jira\domain\entities\UserStory;
use jira\domain\entities\UserStoryStatus;
use jira\domain\ports\driven\UserStoryRepository;
use jira\domain\ports\driving\UserStoryServiceInterface;
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