<?php

namespace Edgar\EzUIAuditBundle\Controller;

use Edgar\EzUIAudit\Form\Data\FilterAuditData;
use Edgar\EzUIAudit\Form\Factory\FormFactory;
use Edgar\EzUIAudit\Form\SubmitHandler;
use Edgar\EzUIAudit\Handler\AuditHandler;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditController extends Controller
{
    /** @var FormFactory  */
    protected $formFactory;

    /** @var SubmitHandler  */
    protected $submitHandler;

    public function __construct(
        FormFactory $formFactory,
        SubmitHandler $submitHandler
    ) {
        $this->formFactory = $formFactory;
        $this->submitHandler = $submitHandler;
    }

    public function dashboardAction(Request $request): Response
    {
        $filterAuditType = $this->formFactory->filterAudit(
            new FilterAuditData()
        );
        $filterAuditType->handleRequest($request);

        if ($filterAuditType->isSubmitted() && $filterAuditType->isValid()) {
            $result = $this->submitHandler->handle($filterAuditType, function (FilterAuditData $data) use ($filterAuditType) {
                $limit = $data->getLimit();
                $page = $data->getPage();

                return $this->render('@EdgarEzUIContentsByType/content/list.html.twig', [
                    'form_filter_audit' => $filterAuditType->createView(),
                ]);
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return $this->render('@EdgarEzUIAudit/audit/dashboard.html.twig', [
            'form_filter_audit' => $filterAuditType->createView(),
        ]);
    }

    public function configureAction(): Response
    {
        return $this->render('@EdgarEzUIAudit/audit/configure.html.twig', [
        ]);
    }

    public function exportAction(): Response
    {
        return $this->render('@EdgarEzUIAudit/audit/export.html.twig', [
        ]);
    }
}
