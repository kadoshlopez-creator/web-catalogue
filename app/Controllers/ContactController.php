<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;

class ContactController extends Controller
{
    public function send()
    {
        $honeypot = $this->request->post('hp_website');
        
        // Anti-bot Protection (Honeypot)
        if (!empty($honeypot)) {
            // It's a bot, silently return success to fool it
            Session::flash('success', 'Su mensaje ha sido enviado');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            return;
        }

        // Rate Limiting (1 request per 60 seconds)
        $lastSubmit = Session::get('last_contact_submit');
        if ($lastSubmit && (time() - $lastSubmit) < 60) {
            Session::flash('error', 'Por favor espere un momento antes de enviar otro mensaje.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            return;
        }

        if (empty($_POST)) {
            Session::flash('error', 'Error técnico: El formulario no envió datos. Asegúrate de que los campos tengan los atributos name="name", name="email" y name="message" en el código fuente.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            return;
        }

        $name = trim($this->request->post('name') ?? $this->request->post('nombre') ?? '');
        $email = trim($this->request->post('email') ?? $this->request->post('correo') ?? '');
        $message = trim($this->request->post('message') ?? $this->request->post('mensaje') ?? '');

        if (empty($name) || empty($email) || empty($message)) {
            Session::flash('error', 'Por favor complete todos los campos. (Recibido: Nombre='.(!empty($name)?'Sí':'No').', Email='.(!empty($email)?'Sí':'No').', Mensaje='.(!empty($message)?'Sí':'No').')');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'El email proporcionado no es válido.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            return;
        }

        // Here we could save to DB (e.g. contact_messages) or send via mail()
        // For now, we simulate success since the system does not have a mailer configured yet.
        // In the future, this can be hooked to a database insertion or PHPMailer logic.
        
        Session::set('last_contact_submit', time());
        Session::flash('success', 'Su mensaje ha sido Enviado');
        
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
}
