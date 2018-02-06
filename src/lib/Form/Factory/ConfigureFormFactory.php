<?php

namespace Edgar\EzUIAudit\Form\Factory;

use Edgar\EzUIAudit\Form\Data\ConfigureAuditData;
use Edgar\EzUIAudit\Form\Type\ConfigureAuditType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ConfigureFormFactory
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

    public function configureAudit(
        ConfigureAuditData $data,
        ?string $name = null
    ): ?FormInterface {
        $name = 'edgarfilterauditstype';

        return $this->formFactory->createNamed(
            $name,
            ConfigureAuditType::class,
            $data,
            [
                'method' => Request::METHOD_POST,
                'csrf_protection' => true,
            ]
        );
    }
}
