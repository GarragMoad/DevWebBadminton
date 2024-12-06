<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
class MailerService
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail(string $to ,string $subject, string $htmlContent): void
    {
        $url = 'https://sandbox.api.mailtrap.io/api/send/3325896';
        $apiKey = 'a1a2e12f415ea20969abe1a6ed56b97a';

        $payload = [
            'from' => [
                'email' => 'devWebBadminton@example.com',
                'name' => 'Mailtrap Test',
            ],
            'to' => [
                [
                    'email' => $to,
                ],
            ],
            'subject' => $subject,
            'html' => $htmlContent,
            'category' => 'Integration Test',
        ];

        try {
            $response = $this->httpClient->request('POST', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            if ($response->getStatusCode() === 200) {
                // Succès
                dump('Email sent successfully!');
            } else {
                // Erreur
                dump('Failed to send email: ' . $response->getContent(false));
            }
        } catch (\Exception $e) {
            // Log l'exception
            dump('Exception while sending email: ' . $e->getMessage());
        }
    }
}