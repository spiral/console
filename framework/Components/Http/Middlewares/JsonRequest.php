<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2015
 */
namespace Spiral\Components\Http\Middlewares;

use Predis\Response\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spiral\Components\Http\MiddlewareInterface;

class JsonRequest implements MiddlewareInterface
{
    /**
     * Handle request generate response. Middleware used to alter incoming Request and/or Response
     * generated by inner pipeline layers.
     *
     * @param ServerRequestInterface $request Server request instance.
     * @param \Closure               $next    Next middleware/target.
     * @param object|null            $context Pipeline context, can be HttpDispatcher, Route or module.
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, \Closure $next = null, $context = null)
    {
        if ($request->getHeader('Content-Type') == 'application/json')
        {
            $jsonBody = $request->getBody()->__toString();

            return $next($request->withParsedBody(json_decode($jsonBody, true)));
        }

        return $next($request);
    }
}