<?php
namespace urlShortenApp\Service;

use urlShortenApp\Model\Url;
use urlShortenApp\Repository\UrlRepository;

class UrlService
{

    protected $urlRepository;

    public function __construct(UrlRepository $urlRepository)
    {
        $this->urlRepository = $urlRepository;
    }

    public function createShortUrl($url, $id_owner)
    {
        $url = new Url(null, $url, $id_owner);

        $url = $this->urlRepository->saveUrl($url);

        return $url;
    }

    public function getUrlById($id_url)
    {
        $url = $this->urlRepository->getUrlById($id_url);

        return $url;
    }
}