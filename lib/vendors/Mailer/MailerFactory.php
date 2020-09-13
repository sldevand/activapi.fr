<?php

namespace Mailer;

use League\OAuth2\Client\Provider\Google;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class MailerFactory
 * @package Mailer
 */
class MailerFactory
{
    /**
     * @return PHPMailer
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public static function create()
    {
        $mail = new PHPMailer();
        //Server settings
        $mail->isSMTP();
        $mail->SMTPDebug = $_ENV['SMTP_DEBUG_ENV'];
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->Port = $_ENV['SMTP_PORT'];
        $mail->SMTPSecure =  PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPAuth = true;
        $mail->AuthType = 'XOAUTH2';

        $email = $_ENV['SMTP_EMAIL'];
        $clientId = $_ENV['OAUTH_CLIENT_ID'];
        $clientSecret = $_ENV['OAUTH_SECRET'];
        $refreshToken = $_ENV['OAUTH_TOKEN'];

        $provider = new Google(
            [
                'clientId' => $clientId,
                'clientSecret' => $clientSecret,
            ]
        );

        $mail->setOAuth(
            new OAuth(
                [
                    'provider' => $provider,
                    'clientId' => $clientId,
                    'clientSecret' => $clientSecret,
                    'refreshToken' => $refreshToken,
                    'userName' => $email,
                ]
            )
        );

        $mail->setFrom($email, 'ActivAPI');

        return $mail;
    }
}
