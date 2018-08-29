<?php
namespace urlShortenApp\Repository;

use urlShortenApp\Model\User;

class UserRepository extends AbstractRepository
{

    public function getUserByName($name)
    {
        $userRow = $this->dbConnection->fetchArray(
            'SELECT id, name, password FROM users WHERE name = ?', [$name]
        );

        return $userRow[0] !== null ?
            new User($userRow[0], $userRow[1], $userRow[2]) :
            null;
    }

    public function saveUser(User $user)
    {
        if ($user->id !== null) {
            $this->dbConnection->executeQuery(
                'UPDATE users SET name = ?, password = ? WHERE id = ?',
                [$user->name, $user->password, $user->id]
            );
        } else {
            $this->dbConnection->executeQuery(
                'INSERT INTO users (name, password) VALUES (?, ?)',
                [$user->name, $user->password]
            );

            $user->id = $this->dbConnection->lastInsertId();
        }


        return $user;
    }

}
