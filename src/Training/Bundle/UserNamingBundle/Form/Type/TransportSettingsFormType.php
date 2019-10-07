<?php

namespace Training\Bundle\UserNamingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransportSettingsFormType extends AbstractType
{
    const NAME = 'training_user_naming_type_transport_setting_form_type';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'url',
            UrlType::class,
            [
                'label' => 'training.usernaming.transport.url.label',
                'required' => true,
                'tooltip' => 'training.usernaming.transport.url.description',
            ]
        );
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'Training\Bundle\UserNamingBundle\Entity\UserNamingTypeTransport']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return self::NAME;
    }
}