<?php

namespace App\Notifications;

// importer les classes nécessaires à l'envoi d'un mail
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreationComteNotification{

    /**
     * Propriété contenant le module d'envoi de mails
     *
     * @var \Swift_Mailer
     */
    private  $mailer;

    /**
     * Propriété contenant l'environnement Twig
     *
     * @var Environment
     */
    private $renderer;

    public function __contruct(\Swift_Mailer $mailer, Environment $renderer){

        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    /**
     * Méthode de notification (envoi de mail)
     *
     * @return void
     */
    public function notify(){
        // On construit le mail
        $message = (new Swift_Message('Notify Order'))
            //Expediteur
            ->setFrom('toupkandimintoua@gmail.com')
            //Destinataire
            ->setTo('no-reply@mta.com')
            //corp du message
            ->setBody(
                $this->renderer->render(
                    'order/add_notif.html.twig'
                ),
                'text/html'
            );
        //on envoie le mail
        $this->mailer->send($message);

    }
}