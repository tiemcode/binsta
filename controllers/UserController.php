<?php

class UserController extends BaseController
{
    public $error = "";
    public function loginGet()
    {
        if (isset($_SESSION['error']['login'])) {
            $this->error = $_SESSION['error']['login'][0];
            unset($_SESSION['error']);
        }
        displayTemplate('users/login.twig', ['error' => $this->error]);
    }
    public function loginPost()
    {
        $userChecked = $this->validUser();
        if ($userChecked) {
            $_SESSION['id'] = $userChecked['id'];
            $_SESSION['name'] = $userChecked['username'];
            header('location:../post');
        } else {
            $_SESSION['error']['login'][] = "Invalid username or password";
            header('location:../user');
        }
    }
    public string $errorreg = '';
    public function registerGet()
    {
        if (isset($_SESSION['errors']['reg'])) {
            $this->errorreg = $_SESSION['errors']['reg'][0];
            unset($_SESSION['errors']);
        }
        displayTemplate('users/registration.twig', ['errorreg' => $this->errorreg]);
    }
    public function registerPost()
    {
        if (!empty($_POST)) {
            if ($this->validRegister()) {
                $user = R::dispense('user');
                $user->username = $_POST['username'];
                $user->password = password_hash($_POST['password2'], PASSWORD_DEFAULT);
                $user->biograph = "no bio yet";
                $user->profilepic = "ah.jpg";
                R::store($user);
                $userChecked = $this->validUser();
                $_SESSION['id'] = $userChecked['id'];
                $_SESSION['name'] = $userChecked['username'];
                header('location:../post');
            } else {
                $this->validRegister();
                header('location:register');
            }
        }
    }
    public function settingGet()
    {
        $this->authorizeUser();
        $changeuser = R::findone("user", "id = ?", [$_SESSION['id']]);
        displayTemplate('users/settings.twig', ['user_change' => $changeuser]);
    }
    public function settingPost()
    {
        if (!empty($_POST)) {
            $passwordErr = "wrong password";
            if (isset($_POST["submit_username"])) {
                $trimedname = trim($_POST['username'], " \n\r\t\v\x00");
                if (password_verify($_POST["password_username"], $this->validPassword())) {
                    $user = R::load("user", $_SESSION['id']);
                    $user->username = $trimedname;
                    R::store($user);
                    header('Location:setting');
                } else {
                    echo $passwordErr;
                }
            }
            //bio 
            if (isset($_POST["submit_bio"])) {
                if (password_verify($_POST["password_bio"], $this->validPassword())) {
                    $trimedbio = trim($_POST['bio'], " \n\r\t\v\x00");
                    $bio = R::load("user", $_SESSION['id']);
                    $bio->biograph = $trimedbio;
                    R::store($bio);

                    header('Location:setting');
                } else {
                    echo $passwordErr;
                }
            }
            if (isset($_POST['submit_password'])) {
                if (password_verify($_POST["password-old"], $this->validPassword())) {
                    if ($_POST['password-new'] === $_POST['password-new2']) {
                        $password = R::load("user", $_SESSION['id']);
                        $password->biograph = $_POST['password-new2'];
                        R::store($password);
                        header('Location:setting');
                    } else {
                        echo 'password not the same';
                    }
                } else {
                    echo 'wrong old password';
                }
            }
            if (isset($_POST['submit_pic'])) {
                if (is_uploaded_file($_FILES['pfp-photo']['tmp_name'])) {
                    // Notice how to grab MIME type.
                    $mime_type = mime_content_type($_FILES['pfp-photo']['tmp_name']);

                    // If you want to allow certain files
                    $allowed_file_types = ['image/png', 'image/jpeg', 'image/jpg'];
                    if (!in_array($mime_type, $allowed_file_types)) {
                        // File type is NOT allowed.
                        $_SESSION["error"]["pfp"] = "wrong file type";
                        return header('Location:setting');
                    }
                    // Set up destination of the file
                    $token = openssl_random_pseudo_bytes(16);
                    $token = bin2hex($token);
                    $destination = "../public/media/";
                    $destination = $destination . $token . ".jpg";

                    // Now you move/upload your file
                    if (move_uploaded_file($_FILES['pfp-photo']['tmp_name'], $destination)) {
                        // File moved to the destination
                        $dbsave = $token . '.jpg';
                        $pic = R::load("user", $_SESSION['id']);
                        $pic->profilepic = $dbsave;
                        R::store($pic);
                        return header('Location:setting');
                    }
                }
            }
        }
    }
    public function profileGet()
    {
        $this->authorizeUser();
        $id =  $_SESSION['id'];
        $idid = R::findAll("user");
        if (strlen($_GET['id']) == 0) {
            header("Location: ../user/profile&id=$id");
        }
        $arrId = [];
        foreach ($idid as $userId) {
            array_push($arrId, $userId['id']);
        }
        if (!in_array($_GET['id'], $arrId)) {
            header("Location: ../user/profile&id=$id");
        }
        $allPost = R::find('post', "user_id = ?", [$_GET['id']]);
        $userprofile = R::findone("user", "id = ?", [$_GET['id']]);
        $comment = R::findAll("comments");

        displayTemplate('users/profile.twig', [
            "profile_user" => $userprofile,
            "allpost" => $allPost,
            "comments" => $comment

        ]);
    }
}
