<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function Contact(Request $request, MailerInterface $mailer): Response
    {
        $Contact = new ContactDTO();
        $form = $this->createForm(ContactType::class, $Contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // test simple mail
            // $mail = (New Email() )
            //     ->to("contact@demo.fr")
            //     ->from($Contact->email)
            //     ->subject("Nouveau message de " . $Contact->name)
            //     ->text($Contact->message);


            // test templated mail
            $mail = (New TemplatedEmail())
                ->to($Contact->destinataire)
                ->from($Contact->email)
                ->subject("Nouveau message de " . $Contact->name)
                ->htmlTemplate('contact/contact.html.twig')
                ->context([ "contact" => $Contact ]);
            
            //send mail
            
            try {
                $mailer->send($mail);
                $this->addFlash('success', 'Votre message a bien été envoyé');
                return $this->redirectToRoute('home');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de l\'envoi du message');
            }
        }

        return $this->render('contact/index.html.twig', [
            "form" => $form
        ]);
    }
}
