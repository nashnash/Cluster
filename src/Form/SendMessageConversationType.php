<?php

namespace App\Form;

use App\Entity\Messages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SendMessageConversationType
 * @package App\Form
 */
class SendMessageConversationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', TextType::class, [
                'attr' => [
                    'class' => 'form-control rounded-0 border-0 py-4 bg-light',
                    'placeholder' => 'Saissez votre message'
                ]
            ])
            ->add('submit',SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-link'
                ],
                'label' => '<i class="fa fa-paper-plane"></i>',
                'label_html' => true
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Messages::class,
        ]);
    }
}
