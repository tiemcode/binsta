<?php

class PostController extends BaseController
{
    public function indexGet()
    {
        // post page
        $this->authorizeUser();
        $result = R::findall("post");
        $comment = R::findAll("comments");
        $like = R::findAll('like');
        displayTemplate("posts/index.twig", [
            'posts' => $result,
            'likes' => $like,
            'comments' => $comment
        ]);
    }
    public function addPostGet()
    {
        $this->authorizeUser();
        $lang = R::findAll("language");
        displayTemplate('posts/addPost.twig', ['lang' => $lang]);
    }

    public function addPostPost()
    {
        $trimedSnippet = trim($_POST['snippet'], " \n\r\t\v\x00");
        $trimedTitle = trim($_POST['title'], " \n\r\t\v\x00");
        if (!empty($trimedSnippet)) {
            if (isset($_POST['submit_post'])) {
                $post = R::dispense('post');
                $post->discript = $trimedTitle;
                $post->code_snippet = $trimedSnippet;
                $post->language = $_POST['lang'];
                $post->user_id = $_SESSION['id'];
                $post->uploaded = date("Y.m.d");
                R::store($post);
                header('Location:../post/');
                return;
            } else {
                header('Location: ../post/addpost');
            }
        } else {
            header('Location: ../post/addpost');
        }
    }
    public function userGet()
    {
        $this->authorizeUser();
        displayTemplate("posts/users.twig", ['users' => R::findall('user')]);
    }
    public function commentPost()
    {

        $trimedComment = trim($_POST['comment'], " \n\r\t\v\x00");
        if (!empty(($trimedComment))) {
            if (isset($_POST['submit_comment'])) {
                $comment = R::dispense('comments');
                $comment->user_id = $_SESSION['id'];
                $comment->post_id = $_POST['hidden'];
                $comment->comment = $trimedComment;
                R::store($comment);
                header('Location:../post/');
                return;
            }
        } else {
            header('Location: ../post/');
        }
    }
    public function likedPost()
    {
        $post = $this->getBeanById('post', $_POST['id']);
        $like = R::findOne('like', 'post_id = ? AND user_id = ?', [$post->id, $_SESSION['id']]);
        if ($like) {
            R::trash($like);
        } else {
            $like = R::dispense('like');
            $like->post_id = $_POST['id'];
            $like->user_id = $_SESSION['id'];
            R::store($like);
        }

        if ($_GET['p'] == 'user/profile') {
            header('Location:../user/profile&id=' . $_GET['id']);
            exit;
        } else {
            header('Location:../post/');
            exit;
        }
    }
}
