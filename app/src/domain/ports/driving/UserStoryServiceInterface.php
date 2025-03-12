<?php

namespace jira\domain\ports\driving;

use jira\domain\entities\UserStory;
use jira\domain\entities\UserStoryStatus;
use jira\domain\exceptions\StatusChangeNotAllowedException;
use jira\domain\exceptions\UserStoryNotFoundException;
use Ramsey\Uuid\Uuid;

interface UserStoryServiceInterface {

    /**
     * @throws UserStoryNotFoundException
     * @throws StatusChangeNotAllowedException
     */
    public function changeStatus(string $id, UserStoryStatus $newStatus): void;

    /**
     * @return UserStory[]
     */
    public function getAllUserStories(): array;
}