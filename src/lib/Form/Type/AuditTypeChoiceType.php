<?php

namespace Edgar\EzUIAudit\Form\Type;

use Edgar\EzUIAuditBundle\Service\AuditService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuditTypeChoiceType extends AbstractType
{
    /** @var AuditService  */
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'choice_loader' => new CallbackChoiceLoader(function () {
                    $auditTypes = [];
                    $auditTypeGroups = $this->auditService->loadAuditTypeGroups();
                    foreach ($auditTypeGroups as $auditTypeGroup) {
                        $auditTypes[$auditTypeGroup] = $this->auditService->loadAuditTypes($auditTypeGroup);
                    }

                    return $auditTypes;
                }),
                'choice_label' => 'name',
                'choice_name' => 'identifier',
                'choice_value' => 'identifier',
            ]);
    }
}
