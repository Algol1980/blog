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
function addUser($email, $firstName, $lastName, $password) {
    $line = json_encode([
        'email' => $email,
        'firstname' => $firstName,
        'lastName' => $lastName,
        'password' => sha1($password)
            ]);
    $userDb = fopen('users.db', 'a+');
    if ($userDb) {
        fwrite($userDb, $line . PHP_EOL);
        fclose($userDb);
        return true;
        
    }
    
}
function isUserExist($email) {
    $userDb = fopen('db/users.db', 'r+');
    if (!$userDb) {
        return FALSE;
    }
    else {
        while (!feof($userDb)) {
            $line = fgets($userDb);
            if($line) {
                $line = json_decode($line, true);
                if ($email == $line['email']) {
                    fclose($userDb);
                    return $line;
                }
            }
        }
        fclose($userDb);
        return FALSE;
    }
}

function checkUser($email, $password) {
    $password = sha1($password);
    $userDb = fopen('db/usersDb', 'r');
        if(!$userDb) {
            return false;
        }
        else {
        while (!feof($userDb)) {
            $line = fgets($userDb);
            if($line) {
                $line = json_decode($line, true);
                if ($email == $line['email'] && $password == $line['password'] ) {
                    fclose($userDb);
                    return true;
                }
            }
        }
        fclose($userDb);
        return FALSE;
    }
}

function addPost($userId, $title, $content, $filePath = false) {
    $userDb = fopen('db/' . $userId . 'db', 'a+');
    if(!userDb) {
        return false;
    }
    
    if ($filePath && is_uploaded_file($filePath)) {
        $pathInfo = pathinfo($filepath);
        $imageName = 'img_' . time() . $pathInfo['extension'];
        move_uploaded_file($filepath, 'img/' . $imageName);
    }
    $line = json_encode([
        'title' => $title,
        'content' = > $content,
        'createdAt' => date("d.m.Y H:i:s"),
        'image' => $imageName;
        ])
}