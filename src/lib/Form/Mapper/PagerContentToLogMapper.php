<?php

namespace Edgar\EzUIAudit\Form\Mapper;

use eZ\Publish\API\Repository\UserService;
use Pagerfanta\Pagerfanta;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;

class PagerContentToLogMapper
{
    /** @var UserService */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function map(Pagerfanta $pager): array
    {
        $data = [];

        foreach ($pager as $content) {
            try {
                $user = $this->userService->loadUser($content->getUserId());
                $userName = $user->getName();
            } catch (NotFoundException $e) {
                $userName = '';
            }

            $data[] = [
                'id' => $content->getId(),
                'user_id' => $content->getUserId(),
                'user_name' => $userName,
                'group_name' => $content->getGroupName(),
                'audit_name' => $content->getAuditName(),
                'infos' => $content->getInfos(),
                'date' => $content->getDate(),
            ];
        }

        return $data;
    }
}
