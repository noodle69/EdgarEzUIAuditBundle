<?php

namespace Edgar\EzUIAudit\Form\Mapper;

use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\UserService;
use Pagerfanta\Pagerfanta;

class PagerContentToExportMapper
{
    /** @var UserService  */
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
                'user_id' => $content->getUserId(),
                'user_name' => $userName,
                'criterias' => $content->getCriterias(),
                'date' => $content->getDate(),
                'status' => '',
                'file' => '',
            ];
        }

        return $data;
    }
}
