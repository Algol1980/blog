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
    } else {
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

function listPosts($userId, $postsPerPage, $page = 1)
{
    $shift = ($page - 1) * $postsPerPage;
    $userPosts = [];
    $filePath = 'db/' . $userId . '.db';
    $file = fopen($filePath, 'r');
    for ($i = 0; $i < $shift; $i++) {
        fgets($file);
    }
    $counter = 0;
    if (!$file) {
        return false;
    } else {
        while (!feof($file) && $counter < $postsPerPage) {
            $line = fgets($file);
            $counter++;
            if ($line) {
                $line = json_decode($line, true);
                array_push($userPosts, $line);
            }
        }
        fclose($file);
        return $userPosts;
    }

}

function getUserById($userId)
{
    $userDb = fopen('db/users.db', 'r+');
    if (!$userDb) {
        return false;
    }
    while (!feof($userDb)) {
        if ($user = fgets($userDb)) {
            $user = json_decode($user, true);
            if ($userId == $user['userId']) {
                fclose($userDb);
                return $user;
            }
        }
    }
}

function getPostCountByUserId($userId)
{

    $filePath = 'db/' . $userId . '.db';
    $file = fopen($filePath, 'r');
    $counter = 0;
    if (!$file) {
        return false;
    } else {
        while (!feof($file)) {
            if ($line = fgets($file)) {
                $counter++;
            }

        }
        fclose($file);
        return $counter;
    }
}

function renderPagination($totalPages, $currentPage)
{

    if (!$currentPage) {
        return false;
    }
    if ($totalPages == 1) {
        return false;
    }

    if ($currentPage > $totalPages) {
        $currentPage = $totalPages;
    }
    $buttons = [];
    $buttons[] = makeButton($currentPage - 1, $currentPage > 1, '«');
    $i = 1;
    while($i <= $totalPages) {
        $isActive = $currentPage != $i;
        $buttons[] = makeButton($i, $isActive);
        $i++;
    }
    $buttons[] = makeButton($currentPage + 1, $currentPage < $totalPages, '»');

    return $buttons;
}

function makeButton($page, $isActive = true, $text = null)
{
    $btn = [];
    $btn['page'] = $page;
    $btn['isActive'] = $isActive;
    $btn['text'] = is_null($text) ? $page : $text;
    return $btn;
}


function getPhotosCount($userId) {
        $filePath = 'db/' . $userId . '.db';
        $file = fopen($filePath, 'r');
        $counter = 0;
        if (!$file) {
            return false;
        } else {
            while (!feof($file)) {
                if ($line = fgets($file)) {
                    $line = json_decode($line, true);
                    if($line['image']) {
                        $counter++;
                    }

                }

            }
            fclose($file);
            return $counter;
        }
}


function getPhotosByUser($userId, $imagesPerPage, $page = 1)
{

    $shift = ($page - 1) * $imagesPerPage;
    $userImages = [];
    $filePath = 'db/' . $userId . '.db';
    $file = fopen($filePath, 'r');
    for ($i = 0; $i < $shift; $i++) {
        fgets($file);
    }
    $counter = 0;
    if (!$file) {
        return false;
    } else {
        while (!feof($file) && $counter < $imagesPerPage) {
            $line = fgets($file);
            if ($line) {
                $line = json_decode($line, true);
                if($line['image']) {
                    array_push($userImages, $line['image']);
                    $counter++;
                }

            }
        }
        fclose($file);
        return $userImages;
    }

}