<?php

namespace App\Controller;


use App\Form\PasswordResetType;
use Doctrine\ORM\EntityManager;
use App\Form\PasswordForgotType;
use Doctrine\Common\Lexer\Token;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles() === 'ROLE_USER') {
                return $this->redirectToRoute('app_home');
            } elseif ($this->getUser()->getRoles() === 'ROLE_ADMIN') {
                return $this->redirectToRoute('admin');
            }
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/mot-de-passe-oublie', name: 'app_pwd_forgot')]
    public function pwdForgot(
        UserRepository $repo,
        Request $request,
        TokenGeneratorInterface $tokenGeneratorInterface,
        MailerInterface $mail,
    ): Response {
        $form = $this->createForm(PasswordForgotType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $repo->findOneBy(['email' => $form->getData()->getEmail()]);

            if ($user) {
                $token = $tokenGeneratorInterface->generateToken();
                $user->setToken($token);
                $repo->save($user);

                if ($repo) {
                    $url = $this->generateUrl('app_pwd_reset', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                    $email = (new Email())
                        ->from('no-reply.blablabike@julien-degermann.fr')
                        // ->to($user->getEmail()) -> replace when all is ok
                        ->to('degermann.julien@gmail.com')
                        ->subject('Réinitialisation de votre mot de passe Blabla Bike')
                        ->html('<p>Lien de réinitialisation de votre mot de passe : </p> <a href=>' . $url);
                    $mail->send($email);
                }
            }
            $this->addFlash('success', 'Un e-mail a été envoyé à l\'adresse indiquée.');

            return $this->render('security/pwd_forgot.html.twig', [
                'form' => $form->createView()
            ]);
        }

        return $this->render('security/pwd_forgot.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route(path: '/reinitialiser-mot-de-passe/{token}', name: 'app_pwd_reset')]
    public function pwdReset(
        Request $request,
        string $token,
        UserRepository $repo,
        UserPasswordHasherInterface $pwdhash
    ): Response {
        $user = $repo->findOneBy(['token' => $token]);
        if (!$user) {
            $this->addFlash('error', 'Oups ! Ce lien n\'est plus valide.');
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(PasswordResetType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user->setPassword(
                    $pwdhash->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                )
                    ->setToken(null);
                $repo->save($user);
                $this->addFlash('success', 'Mot de passe mis à jour');
            } else {
                $this->addFlash('error', 'Une erreur est survenue.');
                return $this->render('security/pwd_reset.html.twig', [
                    'token' => $token,
                    'form' => $form->createView()
                ]);
            }
        }
        if ($request->isMethod('POST')) {
            // $user->setToken(null);
            // $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            // $repo->save($user);
            // $this->addFlash('success', 'Mot de passe mis à jour');
            return $this->redirectToRoute('app_login');
        } else {
            return $this->render('security/pwd_reset.html.twig', [
                'token' => $token,
                'form' => $form->createView()
            ]);
        }
    }
}
