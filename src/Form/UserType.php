<?php

namespace Uloc\ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Uloc\ApiBundle\Entity\User\User;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('name')
            ->add('username')
            ->add('email')
            ->add('password')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ));
    }
}
