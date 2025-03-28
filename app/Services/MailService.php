<?php

namespace App\Services;

class MailService
{
    /**
     * Envía un correo electrónico en formato HTML.
     *
     * @param string $to La dirección de correo electrónico del destinatario..
     * @param string $subject El asunto del correo electrónico.
     * @param string $message El contenido HTML del correo electrónico.
     * @param string $from La dirección de correo electrónico del remitente.
     * @param string $replyTo La dirección de correo electrónico para las respuestas.
     * @return bool Devuelve `true` si el correo se envía correctamente, `false` en caso contrario.
     */
    public function send($to, $subject, $message, $from = 'Noreply@ejemplo.com', $replyTo = 'Noreply@ejemplo.com')
    {
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . $from . '' . "\r\n" .
                    'Reply-To: ' . $replyTo . '' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

        if (mail($to, $subject, $message, $headers)) {
            return true;
        } else {
            return false;
        }
    }
}
