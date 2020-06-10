<?php

namespace App\Form\Project;

use App\Entity\Project\Project;
use App\Form\ChoiceGroupType;
use App\Form\Company\SasFieldType;
use App\Form\Employee\EmployeeFieldType;
use App\Form\Project\Prospector\ProspectorFieldType;
use App\Form\Type\FormattedNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'project.name',
                ]
            )
            ->add(
                'type',
                ChoiceGroupType::class,
                [
                    'label'    => 'project.type',
                    'required' => true,
                    'choices'  => [
                        'project.type.acquisition' => Project::TYPE_ACQUISITION,
                        'project.type.development' => Project::TYPE_DEVELOPMENT,
                    ],
                ]
            )
            ->add(
                'investmentAmount',
                FormattedNumberType::class,
                [
                    'required' => false,
                    'label'    => 'project.investment_amount',
                ]
            )
            ->add(
                'selloutPriceEstimated',
                FormattedNumberType::class,
                [
                    'required' => false,
                    'label'    => 'project.sellout_price_estimated',
                ]
            )
            ->add(
                'selloutPriceReal',
                FormattedNumberType::class,
                [
                    'required' => false,
                    'label'    => 'project.sellout_price_real',
                ]
            )
            ->add(
                'marginEstimated',
                FormattedNumberType::class,
                [
                    'required' => false,
                    'label'    => 'project.margin_estimated',
                ]
            )
            ->add(
                'marginReal',
                FormattedNumberType::class,
                [
                    'required' => false,
                    'label'    => 'project.margin_real',
                ]
            )
            ->add(
                'dateEndEstimated',
                DateType::class,
                [
                    'widget'   => 'single_text',
                    'required' => false,
                    'html5'    => false,
                    'label'    => 'project.date_end_estimated',
                ]
            )
            ->add(
                'dateEndReal',
                DateType::class,
                [
                    'widget'   => 'single_text',
                    'required' => false,
                    'html5'    => false,
                    'label'    => 'project.date_end_real',
                ]
            )
            ->add(
                'riskScore',
                TextType::class,
                [
                    'label'    => 'project.risk_score',
                    'required' => false,
                ]
            )
            ->add(
                'state',
                ChoiceGroupType::class,
                [
                    'label'   => 'project.state',
                    'choices' => [
                        'project.state.pending'            => Project::STATE_PENDING,
                        'project.state.finished'           => Project::STATE_FINISHED,
                        'project.state.abandoned'          => Project::STATE_ABANDONED,
                        'project.state.rejected'           => Project::STATE_REJECTED,
                        'project.state.waiting_validation' => Project::STATE_WAITING_VALIDATION,
                    ],
                ]
            )
            ->add(
                'prospector',
                ProspectorFieldType::class,
                [
                    'label'    => 'project.prospector',
                    'required' => false,
                ]
            )
            ->add(
                'companySas',
                SasFieldType::class,
                [
                    'label'    => 'company.sas',
                    'required' => false,
                ]
            )
            ->add(
                'employees',
                EmployeeFieldType::class,
                [
                    'label'    => 'project.employees',
                    'multiple' => true,
                    'required' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Project::class,
            ]
        );
    }
}
