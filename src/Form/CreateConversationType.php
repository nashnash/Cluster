<?php

namespace App\Form;

use App\Entity\Conversation;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class CreateConversationType extends AbstractType
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('participants', EntityType::class, [
                'class' => User::class,
                'choices' => $this->userRepository->getFriends($this->security->getUser()),
                'multiple' => true,
                'choice_label' => function (User $user) {
                    return ucfirst(strtolower($user->getFirstname())) . ' ' . strtoupper($user->getLastname());
                },
                'attr' => [
                    'class' => 'select-participants',
                    'aria-describedby' => "participantsHelp"
                ],
                'label' => 'Amis',
                'by_reference' => false
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-primary btn-block'
                ],
                'label' => 'Créer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Conversation::class,
        ]);
    }
}
