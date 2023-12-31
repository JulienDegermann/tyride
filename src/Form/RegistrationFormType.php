<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_name', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Nom d\'utilisateur'
            ])
            // ->add('first_name', TextType::class, [
            //     'attr' => [
            //         'class' => 'form-control mb-3'
            //     ],
            //     'label' => 'Prénom'
            // ])
            // ->add('last_name', TextType::class, [
            //     'attr' => [
            //         'class' => 'form-control mb-3'
            //     ],
            //     'label' => 'Nom'
            // ])
            // ->add('city', EntityType::class, [
            //     'class' => City::class,
            //     'attr' => [
            //         'class' => 'form-control mb-3'
            //     ],
            //     'label' => 'Ville'
            // ])
            // ->add('department', EntityType::class, [
            //     'class' => Department::class,
            //     'attr' => [
            //         'class' => 'form-control mb-3'
            //     ],
            //     'label' => 'Département'
            // ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'E-mail'

            ])
            // ->add('mind', EntityType::class, [
            //     'class' => Mind::class,
            //     'attr' => [
            //         'class' => 'form-control mb-3'
            //     ],
            //     'label' => 'Objectif'
            // ])
            // ->add('birth_date', BirthdayType::class, [
            //     'input' => 'datetime_immutable',
            //     'attr' => [
            //         'class' => 'form-control mb-3'
            //     ],
            //     'label' => 'Date de naissance'
            // ])
            // ->add('practice', EntityType::class, [
            //     'class' => Practice::class,
            //     'attr' => [
            //         'class' => 'form-control mb-3'
            //     ],
            //     'label' => 'Pratique'
            // ])
            ->add('RGPDConscents', CheckboxType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'w-auto mb-3 '
                ],
                'label' => 'J\'accepte que ces données soient utilisées sur ce site',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepterles conditions',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d\'entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 12,
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre mot de passe doit contenir au maximum {{ limit }} caractères',
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
