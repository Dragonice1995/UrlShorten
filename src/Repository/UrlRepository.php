<?php
namespace urlShortenApp\Repository;

use urlShortenApp\Model\Url;

class UrlRepository extends AbstractRepository
{

    public function getUrlById($id_url)
    {
        $urlRow = $this->dbConnection->fetchArray(
            'SELECT id_url, url, id_owner FROM urls WHERE id_url = ?', [$id_url]
        );

        return $urlRow[0] !== null ?
            new Url($urlRow[0], $urlRow[1], $urlRow[2]) :
            null;
    }

    public function getUrlsByOwner($id_owner)
    {
        $query = $this->dbConnection->prepare('SELECT id_url, url FROM urls WHERE id_owner = ?');
        $query->execute([$id_owner]);

        $urlRows = $query->fetchAll(PDO::FETCH_CLASS, 'Url');

        return $urlRows[0] !== null ?
            $urlRows :
            null;

        /*$urlRows = $this->dbConnection->fetchAll(
            'SELECT id_url, url FROM urls WHERE id_owner = ?', [$id_owner]
        );

        return $urlRows[0] !== null ?
            $urlRows :
            null;*/
    }

    public function saveUrl(Url $url)
    {
        if ($url->id !== null) {
            $this->dbConnection->executeQuery(
                'UPDATE urls SET url = ?, id_owner = ?,  WHERE id_url = ?',
                [$url->url, $url->id_owner]
            );
        } else {
            $this->dbConnection->executeQuery(
                'INSERT INTO urls (url, id_owner) VALUES (?, ?)',
                [$url->url, $url->id_owner]
            );
            $url->id = $this->dbConnection->lastInsertId();
        }

        return $url;
    }

    public function deleteUrl($id)
    {
        $url = $this->getUrlById($id);
        if ($url->hash !== null) {
            $this->dbConnection->executeQuery(
                'DELETE FROM urls WHERE id_url = ?', [id]
            );

            return true;
        } else {
            return false;
        }
    }

}