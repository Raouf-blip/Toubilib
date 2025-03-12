<?php

namespace jira\application\actions;

use jira\application\ChangeStatusDTO;
use jira\domain\entities\UserStoryStatus;
use jira\domain\exceptions\StatusChangeNotAllowedException;
use jira\domain\exceptions\UserStoryNotFoundException;
use jira\domain\ports\driving\UserStoryServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;

class ChangeStatusAction extends AbstractAction {
    
    protected UserStoryServiceInterface $userStoryService;

    public function __construct(UserStoryServiceInterface $userStoryService) {
        $this->userStoryService = $userStoryService;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $id = $args['id'];
        $data = $request->getParsedBody();
        $newStatus = $data['newStatus'] ?? null;
        if (!$newStatus) {
            throw new HttpBadRequestException($request, "New status is required");
        }

        try {
            $dto = new ChangeStatusDTO(UserStoryStatus::from($newStatus));
            $this->userStoryService->changeStatus($id, $dto->getNewStatus());
        } catch (UserStoryNotFoundException $e) {
            throw new HttpNotFoundException($request, "No user story with id $id");
        } catch (StatusChangeNotAllowedException $e) {
            throw new HttpBadRequestException($request, "Cannot change state to " . $newStatus);
        }
        return $response->withStatus(204);
    }
}
