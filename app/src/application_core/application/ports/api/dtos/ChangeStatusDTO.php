<?php

namespace jira\application_core\application\ports\api\dtos;

use jira\application_core\domain\entities\UserStoryStatus;

class ChangeStatusDTO {

    private UserStoryStatus $newStatus;

    public function __construct(UserStoryStatus $newStatus) {
        $this->newStatus = $newStatus;
    }

    public function getNewStatus(): UserStoryStatus {
        return $this->newStatus;
    }
}