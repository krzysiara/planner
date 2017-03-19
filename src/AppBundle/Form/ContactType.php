<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $ownerProfile = $options['owner_profile'];
        $builder
            ->add('name', 'text')
            ->add('surname', 'text')
            ->add('phone')
            ->add('email', 'email')
            ->add('birthday', 'birthday', [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
                'format' => 'dd-MM-yyyy',
            ])
            ->add('address', 'entity', [
                'class' => 'AppBundle:Location',
                'choice_label' => 'name',
                'multiple' => false,
                'required'=>false,
                'query_builder' => function (EntityRepository $er) use ($ownerProfile){
                    return $er->createQueryBuilder('l')
                        ->where('l.profile = :owner')
                        ->setParameter('owner', $ownerProfile);
                },

            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contact',
            'owner_profile' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_contact';
    }


}
