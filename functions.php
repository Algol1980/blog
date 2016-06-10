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
    $path = tempnam('NOT_EXIST', 'tempDb');
    $userDb = fopen($path, 'a+');
//    $userDb = fopen('db/' . $userId . '.db', 'a+');

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

        $oldUserDb = fopen('db/' . $userId . '.db', 'a+');

        while (!feof($oldUserDb)) {
            $line = fgets($oldUserDb);
            fwrite($userDb, $line);
        }

        fclose($userDb);
        fclose($oldUserDb);
        rename($path, $userDb);
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
    $counter = 0;
    $filePath = 'db/' . $userId . '.db';
    if (file_exists($filePath)) {

        $file = fopen($filePath, 'r');

        if (!$file) {
            return $counter;
        }

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
    while ($i <= $totalPages) {
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


function getPhotosCount($userId)
{
    $counter = 0;
    $filePath = 'db/' . $userId . '.db';
    if (file_exists($filePath)) {
        $file = fopen($filePath, 'r');

        if (!$file) {
            return $counter;
        } else {
            while (!feof($file)) {
                if ($line = fgets($file)) {
                    $line = json_decode($line, true);
                    if ($line['image']) {
                        $counter++;
                    }

                }

            }
            fclose($file);
            return $counter;
        }
    }
}


function getPhotosByUser($userId, $imagesPerPage, $page = 1)
{

    $shift = ($page - 1) * $imagesPerPage;
    $userImages = [];
    $filePath = 'db/' . $userId . '.db';
    if (file_exists($filePath)) {
        $file = fopen($filePath, 'r');
        for ($i = 0; $i < $shift;) {
            if ($line = fgets($file)) {
                $line = json_decode($line, true);
                if ($line['image']) {
                    $i++;
                }
            }
        }
        $counter = 0;
        if (!$file) {
            return false;
        } else {
            while (!feof($file) && $counter < $imagesPerPage) {
                $line = fgets($file);
                if ($line) {
                    $line = json_decode($line, true);
                    if ($line['image']) {
                        array_push($userImages, $line['image']);
                        $counter++;
                    }

                }
            }
            fclose($file);
        }
        return $userImages;
    }

}

function getBloggers()
{
    $userDb = fopen('db/users.db', 'r');
    $result = [];
    $i = 0;
    if (!$userDb) {
        return false;
    }
    while (!feof($userDb) && $i < 20) {

        $line = fgets($userDb);
        if ($line) {
            $line = json_decode($line, true);
            $result[] = [
                'userId' => $line['userId'],
                'name' => $line['firstName'] . ' ' . $line['lastName'],
                'posts' => getPostCountByUserId($line['userId']),
                'images' => getPhotosCount($line['userId'])
            ];
        }
        $i++;
    }
    fclose($userDb);
    return $result;
}

function searchByUser($userId, $postsPerPage, $search, $page = 1)
{
    $postsPerPage = 5;
    $shift = ($page - 1) * $postsPerPage;
    $userPosts = [];
    $filePath = 'db/' . $userId . '.db';

    if (file_exists($filePath)) {
        $file = fopen($filePath, 'r');
        for ($i = 0; $i < $shift;) {
            if ($line = fgets($file)) {
                $post = json_decode($line, true);
                if (
                    stripos($post['title'], $search) !== false ||
                    stripos($post['content'], $search) !== false
                ) {
                    $i++;
                }
            }
        }
        $counter = 0;
        while (!feof($file) && $counter < $postsPerPage) {
            $line = fgets($file);
            if ($line) {
                $line = json_decode($line, true);
                if (stripos($line['title'], $search) !== false ||
                    stripos($line['content'], $search) !== false
                ) {
                    $userPosts[] = $line;
                    $counter++;
                }
            }
        }
        fclose($file);
    }
    return $userPosts;

}


function getSearchPostCount($userId, $search)
{
    $counter = 0;
    $filePath = 'db/' . $userId . '.db';
    if (file_exists($filePath)) {
        $file = fopen($filePath, 'r');

        if (!$file) {
            return $counter;
        } else {
            while (!feof($file)) {
                if ($line = fgets($file)) {
                    $line = json_decode($line, true);
                    if (stripos($line['title'], $search) !== false ||
                        stripos($line['content'], $search) !== false
                    ) {
                        $counter++;
                    }

                }

            }
            fclose($file);
            return $counter;
        }
    }
}