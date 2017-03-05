<?php
namespace F3\Commuter\Test;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\RequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;

class GuzzleFactory
{
    public function createClient(HttpKernelInterface $kernel): Client
    {
        $stack = HandlerStack::create(function (RequestInterface $request) use ($kernel) {
            $server_request = (new ServerRequest(
                $request->getMethod(),
                $request->getUri(),
                $request->getHeaders(),
                $request->getBody(),
                $request->getProtocolVersion()
            ))->withQueryParams(\GuzzleHttp\Psr7\parse_query($request->getUri()->getQuery()));
            $symfony_request = (new HttpFoundationFactory())->createRequest($server_request);
            $symfony_response = $kernel->handle($symfony_request);
            if ($kernel instanceof TerminableInterface) {
                $kernel->terminate($symfony_request, $symfony_response);
            }
            return \GuzzleHttp\Promise\promise_for(
                (new DiactorosFactory())->createResponse($symfony_response)
            );
        });

        return new Client([
            'base_uri' => 'https://localhost',
            'handler'  => $stack,
        ]);
    }
}
