<?php

namespace App\Application\Controller;

use App\Application\Form\MessageType;
use App\Domain\Message\Contrat\SendMessageInterface;
use App\Domain\Message\Message;
use App\Domain\Message\UseCase\SendMessage\SendMessageInput;
use App\Domain\Ride\Contrat\FindMyRidesInterface;
use App\Domain\User\User;
use App\Infrastructure\Repository\MessageRepository;
use App\Infrastructure\Repository\RideRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    public function __construct(
        private readonly SendMessageInterface $sendMessage,
        private readonly FindMyRidesInterface $findMyRides,
    )
    {
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(
        Request           $request,
        MessageRepository $repo,
        RideRepository    $rideRepository
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour accéder à cette page');

            return $this->redirectToRoute('app_home');
        }

        $input = new SendMessageInput($user);
        $form = $this->createForm(MessageType::class, $input, ['attr' => ['class' => 'form-signin, row']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $input = $form->getData();

            ($this->sendMessage)($input);

            $this->addFlash('success', 'Votre message a bien été envoyé');

            return $this->redirectToRoute('app_home');
        }
        $myRides = ($this->findMyRides)($user);

        return $this->render('contact/index.html.twig', [
            'user' => $user,
            'my_next_rides' => $myRides['myNextRides'],
            'my_created_rides' => $myRides['myCreatedRides'],
            'my_prev_rides' => $myRides['allMyRides'],
            'messageForm' => $form->createView(),
        ]);
    }
}
