<?php

namespace Edgar\EzUIAudit\Form\Factory;

use Edgar\EzUIAudit\Form\Data\FilterAuditData;
use Edgar\EzUIAudit\Form\Type\FilterAuditType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormFactory
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

    public function filterAudit(
        FilterAuditData $data,
        ?string $name = null
    ): ?FormInterface {
        $name = 'edgarfilterauditstype';

        return $this->formFactory->createNamed(
            $name,
            FilterAuditType::class,
            $data,
            [
                'method' => Request::METHOD_GET,
                'csrf_protection' => false,
            ]
        );
    }
}
