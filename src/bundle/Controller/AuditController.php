<?php

namespace Edgar\EzAuditBundle\Controller;

use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditController extends Controller
{
    public function dashboardAction(): Response
    {
        return $this->render('@EdgarEzAudit/audit/dashboard.html.twig', [
        ]);
    }

    public function configureAction(): Response
    {
        return $this->render('@EdgarEzAudit/audit/configure.html.twig', [
        ]);
    }

    public function exportAction(): Response
    {
        return $this->render('@EdgarEzAudit/audit/export.html.twig', [
        ]);
    }
}
