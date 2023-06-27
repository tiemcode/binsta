<?php

class BaseController
{
    public function getBeanById($typeOfBean, $queryStringKey)
    {
        $bean = R::findOne($typeOfBean, ' id LIKE ? ', [$queryStringKey]);
        return $bean;
    }

    public function validUser()
    {
        $users = R::findAll('user');
        foreach ($users as $user) {
            if ($user['username'] == $_POST['username']) {
                if (password_verify($_POST['password'], $user['password'])) {
                    $userChecked = R::findOne('user', 'username = ? AND password = ?', [$user['username'], $user['password']]);
                }
            }
        }
        return $userChecked;
    }


    public function authorizeUser()
    {
        if (!isset($_SESSION['id'])) {
            header('Location:../user/login');
        }
    }
    public function validRegister()
    {
        unset($_SESSION['errors']);
        if ($_POST['username'] == '' || $_POST['password'] == '' || $_POST['password2'] == '') {
            $_SESSION['errors']['register'][] = 'All fields are required';
        }

        $user = R::FindOne('user', 'username = ? ', [$_POST['username']]);
        if ($user !== null) {
            if ($_POST['username'] == $user['username']) {
                $_SESSION['errors']['register'][] = 'Username is already in use';
            }
        }

        if ($_POST['password'] != $_POST['password2']) {
            $_SESSION['errors']['register'][] = 'Passwords did not match';
        }

        return empty($_SESSION['errors']);
        header('Location: ../post/');
    }
    public function validPassword()
    {
        $password = R::findOne("user", "id like ? ", [$_SESSION['id']]);
        return $password['password'];
    }
}
