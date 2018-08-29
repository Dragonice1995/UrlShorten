<?php
namespace urlShortenApp\Controller;

use urlShortenApp\Model\User;
use urlShortenApp\Service\UrlService;
use urlShortenApp\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController
{

    protected $userService;
    protected $urlService;

    public function __construct(UserService $userService, UrlService $urlService)
    {
        $this->userService = $userService;
        $this->urlService = $urlService;
    }

    /**
     * @param Request $request
     * @return User|bool
     */
    protected function getUserByAuthorization(Request $request)
    {
        $username = $request->server->get('PHP_AUTH_USER');
        $password = $request->server->get('PHP_AUTH_PW');

        $user = $this->userService->getUserByName($username);

        return ($user !== null && $user->password === $password) ? $user : false;
    }

    protected function createUnathorizedResponse()
    {
        return new JsonResponse(
            ['error' => 'not authorized'],
            Response::HTTP_UNAUTHORIZED,
            [
                'WWW-Authenticate' => 'Basic realm="urlShorten API"'
            ]
        );
    }

    protected function createErrorResponse($message)
    {
        return new JsonResponse(
            ['error' => $message],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

}