<?php

namespace Edgar\EzUIAudit\Form\Factory;

use Edgar\EzUIAudit\Form\Data\ExportAuditData;
use Edgar\EzUIAudit\Form\Type\ExportAuditType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ExportFormFactory
{
    /** @var FormFactoryInterface $formFactory */
    protected $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function exportAudit(
        ExportAuditData $data,
        ?string $name = null
    ): ?FormInterface {
        $name = 'edgarexportauditstype';

        return $this->formFactory->createNamed(
            $name,
            ExportAuditType::class,
            $data,
            [
                'method' => Request::METHOD_POST,
                'csrf_protection' => true,
            ]
        );
    }
}
