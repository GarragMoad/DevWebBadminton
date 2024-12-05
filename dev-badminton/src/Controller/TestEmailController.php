<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\MailerService;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TestEmailController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/test/email')]
    public function sendEmail(): Response
    {
        $response = $this->httpClient->request('POST', 'https://sandbox.api.mailtrap.io/api/send/3325896', [
            'headers' => [
                'Authorization' => 'Bearer a1a2e12f415ea20969abe1a6ed56b97a',
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'from' => ['email' => 'hello@example.com', 'name' => 'Mailtrap Test'],
                'to' => [['email' => "garragmouad8@gmail.com"]],
                'subject' => "test",
                'text' => "test done",
                'category' => 'Integration Test',
            ],
        ]);


        if ($response->getStatusCode() !== 200) {
            return new Response('Failed to send email:');
        }

        else if($response->getStatusCode() == 200){
            return new Response('Email sent successfully!');
        }
    }


}
