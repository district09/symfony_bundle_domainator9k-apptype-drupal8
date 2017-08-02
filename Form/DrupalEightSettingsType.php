<?php

namespace DigipolisGent\Domainator9k\AppTypes\DrupalEightBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DigipolisGent\Domainator9k\AppTypes\DrupalEightBundle\Entity\DrupalEightSettings;

class DrupalEightSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('installProfile', TextType::class, ['data' => 'digipolis'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => DrupalEightSettings::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'drupaleight_deploy_type_settings';
    }
}
