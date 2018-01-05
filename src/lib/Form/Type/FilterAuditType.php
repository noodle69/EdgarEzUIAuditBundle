<?php

namespace Edgar\EzUIAudit\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterAuditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'audit_types',
                AuditTypeChoiceType::class,
                [
                    'label' => 'Signals',
                    'multiple' => true,
                    'expanded' => false,
                    'required' => false,
                ]
            )
            ->add(
                'date_start',
                DateType::class,
                [
                    'widget' => 'choice',
                ]
            )
            ->add(
                'date_end',
                DateType::class,
                [
                    'widget' => 'choice',
                ]
            )
            ->add('page', HiddenType::class)
            ->add('limit', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
            ]);
    }
}
