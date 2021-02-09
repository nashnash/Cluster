<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Validator\Siren;
use App\Validator\Siret;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $user=$options["data"];
        if(in_array("ROLE_PRO",$user->getRoles())){
            $builder
                ->add('firstname', TextType::class, [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'The firstname cannot be empty'
                        ])
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Prénom'
                    ]
                ])
                ->add('lastname', TextType::class, [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'The lastname cannot be empty'
                        ])
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Nom'
                    ]
                ])
                ->add('email', EmailType::class, [
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'email@email.com'
                    ]
                ])
                ->add('siret', TextType::class, [
                    'constraints' => [
                        new Siret()
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'SIRET'
                    ]
                ])
                ->add('siren', TextType::class, [
                    'constraints' => [
                        new Siren()
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'SIREN'
                    ]
                ])->add('address',TextType::class,[
                    'constraints' => [
                        new NotBlank([
                            'message' => 'The address cannot be empty'
                        ])
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Address'
                    ]
                ])
                ->add('city',TextType::class,[
                    'constraints' => [
                        new NotBlank([
                            'message' => 'The city cannot be empty'
                        ])
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'City'
                    ]
                ])
            ;
        }else {
            $builder
                ->add('firstname', TextType::class, [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'The firstname cannot be empty'
                        ])
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Prénom'
                    ]
                ])
                ->add('lastname', TextType::class, [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'The lastname cannot be empty'
                        ])
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Nom'
                    ]
                ])
                ->add('email', EmailType::class, [
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'email@email.com'
                    ]
                ])
                ->add('address',TextType::class,[
                    'constraints' => [
                        new NotBlank([
                            'message' => 'The address cannot be empty'
                        ])
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Address'
                    ]
                ])
                ->add('city',TextType::class,[
                    'constraints' => [
                        new NotBlank([
                            'message' => 'The city cannot be empty'
                        ])
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'City'
                    ]
                ])
            ;
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
