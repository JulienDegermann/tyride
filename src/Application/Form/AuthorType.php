<?php

namespace App\Application\Form;

use App\Domain\User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('email', EmailType::class, [
        'label' => 'E-mail',
        'required' => true,
        'attr' => ['class' => 'form-control mb-3 border border-dark'],
      ])
      ->add('nameNumber', TextType::class, [
        'label' => 'Pseudo',
        'required' => true,
        'attr' => ['class' => 'form-control mb-3 border border-dark'],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}
