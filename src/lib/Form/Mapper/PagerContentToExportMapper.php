<?php

namespace Edgar\EzUIAudit\Form\Mapper;

use Edgar\EzUIAudit\Repository\EdgarEzAuditExportRepository;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\UserService;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Translation\Translator;

class PagerContentToExportMapper
{
    /** @var UserService */
    private $userService;

    /** @var Translator */
    private $translator;

    public function __construct(UserService $userService, Translator $translator)
    {
        $this->userService = $userService;
        $this->translator = $translator;
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

            $status = $this->translator->trans('KO', [], 'edgarezuiaudit');
            switch ($content->getStatus()) {
                case EdgarEzAuditExportRepository::STATUS_INIT:
                    $status = $this->translator->trans('Init', [], 'edgarezuiaudit');
                    break;
                case EdgarEzAuditExportRepository::STATUS_PROGRESS:
                    $status = $this->translator->trans('In progress', [], 'edgarezuiaudit');
                    break;
                case EdgarEzAuditExportRepository::STATUS_OK:
                    $status = $this->translator->trans('OK', [], 'edgarezuiaudit');
                    break;
            }

            $data[] = [
                'id' => $content->getId(),
                'user_id' => $content->getUserId(),
                'user_name' => $userName,
                'audits' => $content->getAudits(),
                'date_start' => $content->getDateStart(),
                'date_end' => $content->getDateEnd(),
                'date' => $content->getDate(),
                'status' => $status,
                'file' => $content->getFile() ? basename($content->getFile()) : false,
            ];
        }

        return $data;
    }
}
