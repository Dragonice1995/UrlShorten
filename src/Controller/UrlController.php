<?php
namespace urlShortenApp\Controller;

use Doctrine\DBAL\DBALException;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UrlController extends AbstractController
{

    public function createShortUrl(Request $request)
    {
        $user = $this->getUserByAuthorization($request);
        if ($user === false) {
            return $this->createUnathorizedResponse();
        }

        $url = $this->urlService->createShortUrl(
            $request->get('url'),
            $user->id
        );
        $shortUrl = rtrim(strtr(base64_encode($url->id), '+/', '-_'), '=');

        return new JsonResponse(
            [
                'shortUrl' => $shortUrl
            ],
            Response::HTTP_CREATED
        );
    }

    public function redirectToUrl(Request $request)
    {
        $hash = $request->get('hash');
        $id_url = base64_decode(strtr($hash, '-_', '+/'), true);
        if ($id_url !== false) {
            $url = $this->urlService->getUrlById($id_url);
            if ($url === null) {
                return $this->createErrorResponse('url not exist');
            } else {
                return new JsonResponse(
                    [
                        'url' => $url->url
                    ],
                    Response::HTTP_FOUND,
                    header('Location: ' . $url->url)
                );
            }
        } else {
            return $this->createErrorResponse('bad hash');
        }
    }
}