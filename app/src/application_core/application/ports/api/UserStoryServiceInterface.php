<?php

namespace jira\application_core\application\ports\api;

use jira\application_core\domain\entities\UserStory;
use jira\application_core\domain\entities\UserStoryStatus;
use jira\application_core\domain\exceptions\StatusChangeNotAllowedException;
use jira\application_core\domain\exceptions\UserStoryNotFoundException;
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