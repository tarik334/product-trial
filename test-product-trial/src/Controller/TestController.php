<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TestController extends AbstractController
{
    protected string $accessToken = '';

    public function __construct(
        protected HttpClientInterface $client,
    ) {}

    protected function getResponse(string $method, string $url, array $options = []): Response
    {
        $options['base_uri'] = $this->getParameter('ws_url');

        if ($this->accessToken) {
            $options['auth_bearer'] = $this->accessToken;
        }

        $response = $this->client->request($method, $url, $options);

        $statusCode = $response->getStatusCode();
        $content = $response->getContent(false);

        $json = json_decode($content, true);
        $prettyJson = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return new Response(
            "Status: $statusCode\n\n" . $prettyJson,
            200,
            ['Content-Type' => 'text/plain'],
        );
    }
}