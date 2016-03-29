<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextareaType::class, [
                'attr' => [
                    'cols' => 90,
                    'rows' => 10,
                    'placeholder' => 'Enter your text',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Edit',
                'attr' => [
                    'class' => 'btn-success',
                ],
            ]);
    }

    public function getBlockPrefix()
    {
        return 'task_edit';
    }
}
