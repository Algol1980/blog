<?php


function listErrors($errArr)
{
    $errorMessage = '<ul>';
    foreach ($errArr as $value) {
        $errorMessage .= '<li>' . $value . '</li>';
    }
    $errorMessage .= '</ul>';
    return $errorMessage;
}

function addUser($email, $firstName, $lastName, $password)
{
    //TODO: implement user id - Realized
    $line = [
        'userId' => uniqid(),
        'email' => $email,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'password' => sha1($password)
    ];
    $userDb = fopen('db/users.db', 'a+');
    if ($userDb) {
        fwrite($userDb, json_encode($line) . PHP_EOL);
        fclose($userDb);
        return $line;


    }

}

function isUserExist($email)
{
    $userDb = fopen('db/users.db', 'r+');
    if (!$userDb) {
        return FALSE;
    } else {
        while (!feof($userDb)) {
            $line = fgets($userDb);
            if ($line) {
                $line = json_decode($line, true);
                if ($email == $line['email']) {
                    fclose($userDb);
                    return $line;
                }
            }
        }
        fclose($userDb);
        return false;
    }
}

function checkUser($email, $password)
{
    $password = sha1($password);
    $userDb = fopen('db/users.db', 'r');
    if (!$userDb) {
        return false;
    } else {
        while (!feof($userDb)) {
            $line = fgets($userDb);
            if ($line) {
                $line = json_decode($line, true);
                if ($email == $line['email'] && $password == $line['password']) {
                    fclose($userDb);
                    return $line;
                }
            }
        }
        fclose($userDb);
        return false;
    }
}

function addPost($userId, $title, $content, $filePath = false)
{
    $userDb = fopen('db/' . $userId . '.db', 'a+');
    if (!$userDb) {
        return false;
    }
    else {
    if ($filePath && is_uploaded_file($filePath)) {
        //TODO: check image (getimagesize) - Realized
        $types = ['image/gif' => 'gif', 'image/jpeg' => 'jpg', 'image/png' => 'png'];
        $imgInfo = getimagesize($filePath);
        if ($imgInfo && array_key_exists($imgInfo['mime'], $types)) {
            $imageName = 'img_' . time() . '.' . $types[$imgInfo['mime']];
            move_uploaded_file($filePath, 'img/' . $imageName);
        }
    }

    fwrite($userDb, json_encode([
        'title' => $title,
        'content' => $content,
        'createdAt' => date("d.m.Y H:i:s"),
        'image' => $imageName
    ]) . PHP_EOL);

    fclose($userDb);
    return true;
    }
}

function listPosts($userId, $page=1) {
    $postsPerPage = 20;
    $shift = ($page - 1) * $postsPerPage;
    $userPosts = [];
    $filePath = 'db/' . $userId . '.db';
    $file = fopen($filePath, 'r');
       for($i=0; $i < $shift; $i++) {
           fgets($file);
       }
    $counter = 0;
    if (!$file) {
        return false;
    }
    else {
        while (!feof($file) && $counter < $postsPerPage) {
            $line = fgets($file);
            $counter++;
            if ($line) {
                $line = json_decode($line, true);
                array_shift($userPosts, $line);
            }
        }
        fclose($file);
        return $userPosts;
    }

}

function getUserById($userId) {
    $userDb = fopen('db/users.db', 'r+');
    if (!$userDb) {
        return FALSE;
    } else {
        while (!feof($userDb)) {
            if ($line = fgets($userDb)) {
                $user = json_decode($line, true);
                if ($userId == $line['userId']) {
                    fclose($userDb);
                    return $user;
                }
             }
        }
        fclose($userDb);
        return false;
    
}
}

function getPostCountByUserId ($userId) {
    $filePath = 'db/' . $userId . '.db';
    $file = fopen($filePath, 'r');
    $counter++;
    if (!$file) {
        return false;
    }
    else {
        while (!feof($file)) {
            if($line = fgets($file)) {
            $counter++;
                    }
        fclose($file);
        return $counter;
    }
}
}