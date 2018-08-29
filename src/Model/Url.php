<?php
namespace urlShortenApp\Model;

class Url
{
    public $id;

    public $url;

    public $id_owner;

    public function __construct($id, $url, $id_owner)
    {
        $this->id = $id;
        $this->url = $url;
        $this->id_owner = $id_owner;
    }
}