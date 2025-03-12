<?php

namespace jira\domain\ports\driven;

use jira\domain\entities\UserStory;
use jira\domain\entities\UserStoryStatus;
use jira\domain\UserStoryNotFoundException;
use Ramsey\Uuid\Uuid;

interface UserStoryRepository {

    /**
     * @return UserStory[]
     */
    public function findAll(): array;

    /**
     * @return UserStory
     * @throws UserStoryNotFoundException
     */
    public function findById(string $id): UserStory;

    /**
     */
    public function save(UserStory $userStory): void;
}