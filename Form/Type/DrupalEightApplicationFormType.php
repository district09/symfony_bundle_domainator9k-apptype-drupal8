<?php


namespace DigipolisGent\Domainator9k\AppTypes\DrupalEightBundle\Form\Type;

use DigipolisGent\Domainator9k\AppTypes\DrupalEightBundle\Entity\DrupalEightApplication;
use DigipolisGent\Domainator9k\CoreBundle\Form\Type\AbstractApplicationFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DrupalEightApplicationFormType
 * @package DigipolisGent\Domainator9k\AppTypes\DrupalEightBundle\Form\Type
 */
class DrupalEightApplicationFormType extends AbstractApplicationFormType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('installProfile');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('data_class', DrupalEightApplication::class);
    }
}
