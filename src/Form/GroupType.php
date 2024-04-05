<?php

namespace Uloc\ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Uloc\ApiBundle\Entity\User\Group;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('active')
            ->add('roles', null, [
                'required' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'allow_extra_fields' => true
            ])
            ->add('acl', CollectionType::class, [
                'required' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'allow_extra_fields' => true
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Group::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ));
    }
}
