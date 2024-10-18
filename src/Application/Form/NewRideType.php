<?php

namespace App\Application\Form;

use App\Domain\Location\City;
use App\Domain\PracticeDetail\Mind;
use App\Domain\PracticeDetail\Practice;
use App\Domain\Ride\Ride;
use App\Domain\Ride\UseCase\CreateRide\NewRideInput;
use App\Domain\User\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewRideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User $user */
        $user = $options['user'];

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la sortie',
                'required' => true,
                'attr' => ['class' => 'form-control mb-3 border border-dark border border-dark'],
            ])
            ->add('distance', IntegerType::class, [
                'label' => 'Distance estimée (kms)',
                'required' => true,
                'attr' => ['class' => 'form-control mb-3 border border-dark border border-dark'],
            ])
            ->add('ascent', IntegerType::class, [
                'label' => 'Dénivelé positif estimé (m)',
                'required' => true,
                'attr' => ['class' => 'form-control mb-3 border border-dark border border-dark'],
            ])
            ->add('maxParticipants', IntegerType::class, [
                'label' => 'Nombre maximum de participants',
                'required' => true,
                'attr' => ['class' => 'form-control mb-3 border border-dark border border-dark'],
            ])
            ->add('averageSpeed', IntegerType::class, [
                'label' => 'Vitesse moyenne de la sortie (km/h)',
                'required' => true,
                'attr' => ['class' => 'form-control mb-3 border border-dark border border-dark'],
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'Date et heure de départ',
                'required' => true,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark border border-dark',
                    'data-date-format' => 'dd-mm-yy HH:ii',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description et informations complémentaires',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark border border-dark',
                    'rows' => '10',
                ],
            ])
            ->add('mind', EntityType::class, [
                'label' => 'Objectif de la sortie',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark border border-dark',
                ],
                'class' => Mind::class,
                'data' => $user->getMind(),
            ])
            ->add('practice', EntityType::class, [
                'label' => 'Pratique',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark',
                ],
                'class' => Practice::class,
                'data' => $user->getPractice(),
            ])
            ->add('startCity', EntityType::class, [
                'label' => 'Ville de départ',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark text-capitalize',
                ],
                'class' => City::class,
                'query_builder' => function (EntityRepository $er) use ($user): QueryBuilder {
                    return $er->createQueryBuilder('c')
                        ->leftJoin('c.department', 'd')
                        ->andWhere('c.department = :d')
                        ->setParameter(':d', $user->getDepartment())
                        ->orderBy('c.name', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NewRideInput::class,
            'user' => User::class,
        ]);
    }
}
