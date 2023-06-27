<?php

require_once   'vendor/autoload.php';
require_once 'rb.php';
R::setup(
    'mysql:host=localhost;dbname=binsta',
    'bit_academy',
    'bit_academy'
); //for both mysql or mariaDB
R::nuke();


$users = [
    [
        'username' => "admin",
        "password" => 'password',
        "biograph" => "no bio yet",
        "profilepic" => "ah.jpg",
        "likedPost" => ''
    ],
    [
        'username' => "jack sixpack",
        "password" => 'test',
        "biograph" => "no bio yet",
        "profilepic" => "ah.jpg",
        "likedPost" => ''
    ]
];
foreach ($users as $u) {
    $user = R::dispense('user');
    $user->username = $u['username'];
    $user->password = password_hash($u['password'], PASSWORD_DEFAULT);
    $user->biograph = $u['biograph'];
    $user->profilepic = $u['profilepic'];
    $user->likedPosts = $u['likedPost'];
    R::store($user);
}

$posts = [
    [

        "discript" => 'test',
        "codeSnippet" => "echo'dit is een test' ",
        "userId" => '1',
        "language" => 'PHP',
        "uploaded" => date("Y.m.d")
    ],
    [
        "discript" => 'syntax highligt',
        "codeSnippet" => "<pre><code class='language-{{language}}'>{{item.code_snippet}}</code></pre> ",
        "userId" => '2',
        "language" => 'HTML',
        "uploaded" => date("Y.m.d")
    ]
];

foreach ($posts as $p) {
    $post = R::dispense('post');
    $post->discript = $p['discript'];
    $post->codeSnippet = $p['codeSnippet'];
    $post->userId = $p['userId'];
    $post->language = $p['language'];
    $post->uploaded = $p["uploaded"];
    R::store($post);
}
$languages = [
    ['language' => 'PHP'],
    ['language' => 'HTML'],
    ['language' => 'CSS'],
    ['language' => "javascript"],
    ['language' => 'SQL'],
    ['language' => 'plaintext']
];

foreach ($languages as $l) {
    $lang = R::dispense('language');
    $lang->language = $l['language'];
    R::store($lang);
}
$comments = [
    [
        "userId" => '2',
        "postId" => '1',
        "likes" => '1',
        "comment" => "dat is php"
    ],
    [
        "userId" => '1',
        "postId" => '2',
        "likes" => '0',
        "comment" => "wow"
    ]
];
foreach ($comments as $c) {
    $comment = R::dispense('comments');
    $comment->userId = $c['userId'];
    $comment->postId = $c['postId'];
    $comment->comment = $c['comment'];
    R::store($comment);
}
$likes = [
    [
        "userid" => '1',
        "postid" => '2'
    ]
];
foreach ($likes as $l) {
    $like = R::dispense('like');
    $like->userId = $l['userid'];
    $like->postId = $l['postid'];
    R::store($like);
}
echo count($users) . " user made" . PHP_EOL;
echo count($posts) . " posts made " . PHP_EOL;
echo count($languages) . " langs added" . PHP_EOL;
echo count($comments) . " comments added" . PHP_EOL;
echo count($likes) . " postest liked";
