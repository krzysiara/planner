<?php
/**
 * Class NoteType
 */

namespace AppBundle\Form;

use AppBundle\Entity\Note;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NoteType
 * @package AppBundle\Form
 */
class NoteType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('description', 'textarea', [
                'attr' => ['class' => 'tinymce', ],
            ])
            ->add('type', 'hidden', ['data' => $options['note_type']])
            ->add('color', 'entity', [
                'class' => 'AppBundle:Color',
                'choice_label' => 'name',
                'multiple' => false,
                'required' => false,
            ]);

        if ($options['chose_parent_entity']) {
            $ownerProfile = $options['owner'];
            if ($options['note_type'] == Note::EVENT_TYPE) {
                $builder->add('event', 'entity', [
                    'class' => 'AppBundle:Event',
                    'choice_label' => 'title',
                    'multiple' => false,
                    'required' => true,
                    'query_builder' => function (EntityRepository $er) use ($ownerProfile) {
                        return $er->createQueryBuilder('e')
                            ->where('e.profile = :owner')
                            ->setParameter('owner', $ownerProfile);
                    },
                ]);
            } elseif ($options['note_type'] == Note::LOCATION_TYPE) {
                $builder ->add('location', 'entity', [
                    'class' => 'AppBundle:Location',
                    'choice_label' => 'name',
                    'multiple' => false,
                    'required' => true,
                    'query_builder' => function (EntityRepository $er) use ($ownerProfile) {
                        return $er->createQueryBuilder('l')
                            ->where('l.profile = :owner')
                            ->setParameter('owner', $ownerProfile);
                    },
                ]);
            } elseif ($options['note_type'] == Note::CONTACT_TYPE) {
                $builder ->add('contact', 'entity', [
                    'class' => 'AppBundle:Contact',
                    'choice_label' => 'fullName',
                    'multiple' => false,
                    'required' => true,
                    'query_builder' => function (EntityRepository $er) use ($ownerProfile) {
                        return $er->createQueryBuilder('l')
                            ->where('l.profile = :owner')
                            ->setParameter('owner', $ownerProfile);
                    },
                ]);
            }
        }
    }

    /**
     * configureOptions
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Note',
            'note_type' => Note::EVENT_TYPE,
            'owner' => null,
            'chose_parent_entity' => true,
        ));
    }

    /**
     * getBlockPrefix
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_note';
    }
}
