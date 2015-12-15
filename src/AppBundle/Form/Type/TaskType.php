<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', 'textarea', [
                'attr' => [
                    'cols' => 90,
                    'rows' => 10,
                    'placeholder' => 'Enter your text',
                ],
            ])
            ->add('submit', 'submit', [
                'label' => 'Add',
                'attr' => [
                    'class' => 'btn-default',
                ]
            ]);
    }

    public function getName()
    {
        return 'text';
    }
}
