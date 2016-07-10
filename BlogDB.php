<?php

/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 10.07.2016
 * Time: 20:34
 */
class BlogDB
{
    private static $instance;

    private function __construct()
    {
        $filePath = 'db/users.db';
        $this->log = self::openLog();
        if ($this->log) {
            fwrite($this->log, date('d.m.Y H:i:s') . "\tConnecting to databse... " . $filePath . PHP_EOL);
        }
        if (!$this->connect = fopen($filePath, 'a+')) {
            fwrite($this->log, date('d.m.Y H:i:s') . "\tUnable to connect to databse... " . $filePath . PHP_EOL);
            return false;
        }
        fseek($this->connect, 0);
        fwrite($this->log, date('d.m.Y H:i:s') . "\tDatabase open for reading and writing " . $filePath . PHP_EOL);
    }

    private static function openLog()
    {
        $filepath = __DIR__ . '/log.txt';
        if ($log = fopen($filepath, 'a+')) {
            return $log;
        } else {
            return false;
        }

    }

    public static function getInstance()
    { // получить экземпляр данного класса
        if (self::$instance === null) { // если экземпляр данного класса  не создан
            self::$instance = new self;  // создаем экземпляр данного класса
        }
        return self::$instance; // возвращаем экземпляр данного класса
    }

    private function __clone()
    {
    }

    private function _wakeup_()
    {
    }

    public static function getBloggers()
    {
        $db = self::getInstance();
        $result = [];
        $i = 0;
        while (!feof($db->connect) && $i < 20) {
            $line = fgets($db->connect);
            if ($line) {
                $line = json_decode($line, true);
                $result[] = [
                    'userId' => $line['userId'],
                    'name' => $line['firstName'] . ' ' . $line['lastName'],
                    'posts' => self::getPostCountByUserId($line['userId']),
                    'images' => self::getPhotosCount($line['userId'])
                ];
            }
            $i++;
        }
        fseek($db->connect, 0);
        return $result;
    }

    public static function getPostCountByUserId($userId)
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


    public static function getPhotosCount($userId)
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

    public static function listErrors($errArr)
    {
        $errorMessage = '<ul>';
        foreach ($errArr as $value) {
            $errorMessage .= '<li>' . $value . '</li>';
        }
        $errorMessage .= '</ul>';
        return $errorMessage;
    }


    public static function getAllPhotosByUser($userId)
    {
        $userImages = [];
        $filePath = 'db/' . $userId . '.db';
        if (file_exists($filePath)) {
            $file = fopen($filePath, 'r');
            while (!feof($file)) {
                $line = fgets($file);
                if ($line) {
                    $line = json_decode($line, true);
                    if ($line['image']) {
                        array_push($userImages, $line['image']);
                    }
                }
            }
            fclose($file);
        }
        return $userImages;
    }


    public static function getUserById($userId)
    {
        $db = self::getInstance();
        while (!feof($db->connect)) {
            if ($user = fgets($db->connect)) {
                $user = json_decode($user, true);
                if ($userId == $user['userId']) {
                    fseek($db->connect, 0);
                    return $user;
                }
            }
        }
    }

    public static function totalSearchPostCount($search)
    {
        $counter = 0;
        $db = self::getInstance();
        while (!feof($db->connect)) {
            if ($line = fgets($db->connect)) {
                $line = json_decode($line, true);
                $counter += self::getSearchPostCount($line['userId'], $search);
            }
        }
        fseek($db->connect, 0);
        return $counter;
    }


    public static function getSearchPostCount($userId, $search)
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


    public static function searchByUser($userId, $postsPerPage, $search, $page = 1)
    {
        $shift = ($page - 1) * $postsPerPage;
        $userPosts = [];
        $filePath = 'db/' . $userId . '.db';

        if (file_exists($filePath)) {
            $file = fopen($filePath, 'r');
            $i = 0;
            while (!feof($file) && $i < $shift) {
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


    public static function totalSearchPosts($search, $postsPerPage, $page = 1)
    {
        $result = [];
        $shift = ($page - 1) * $postsPerPage;
        $db = self::getInstance();
        while (!feof($db->connect)) {
            if ($line = fgets($db->connect)) {
                $line = json_decode($line, true);
                if ($posts = self::searchPosts($line['userId'], $postsPerPage, $search, $shift)) {
                    $result[$line['userId']] = $posts[0];
                    $shift = $posts[1];
                    if (count($posts) < $postsPerPage) {
                        $postsPerPage -= count($posts[0]);
                    } else {
                        break;
                    }
                }
            }
            fseek($db->connect, 0);
            return $result;
        }

    }


    public static function searchPosts($userId, $postsPerPage, $search, $shift)
    {

        $userPosts = [];
        $filePath = 'db/' . $userId . '.db';

        if (file_exists($filePath)) {
            $file = fopen($filePath, 'r');

            while (!feof($file) && $shift > 0) {
                if ($line = fgets($file)) {
                    $post = json_decode($line, true);
                    if (
                        stripos($post['title'], $search) !== false ||
                        stripos($post['content'], $search) !== false
                    ) {
                        $shift--;
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
        return [$userPosts, $shift];

    }


    public static function getPostPosition($userId, $post)
    {
        $counter = 1;
        $filePath = 'db/' . $userId . '.db';
        if (file_exists($filePath)) {
            $file = fopen($filePath, 'r');

            if (!$file) {
                return $counter;
            } else {
                while (!feof($file)) {
                    if ($line = fgets($file)) {
                        $line = json_decode($line, true);
                        if ($line != $post) {
                            $counter++;
                        } else {
                            fclose($file);
                            return $counter;
                        }

                    }

                }
                fclose($file);
                return false;

            }
        }
    }


    public static function renderPagination($totalPages, $currentPage)
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
        $buttons[] = self::makeButton($currentPage - 1, $currentPage > 1, '«');
        $i = 1;
        while ($i <= $totalPages) {
            $isActive = $currentPage != $i;
            $buttons[] = self::makeButton($i, $isActive);
            $i++;
        }
        $buttons[] = self::makeButton($currentPage + 1, $currentPage < $totalPages, '»');

        return $buttons;
    }

    public static function makeButton($page, $isActive = true, $text = null)
    {
        $btn = [];
        $btn['page'] = $page;
        $btn['isActive'] = $isActive;
        $btn['text'] = is_null($text) ? $page : $text;
        return $btn;
    }


    public static function addPost($userId, $title, $content, $filePath = false)
    {
        $path = tempnam('NOT_EXIST', 'tempDb');
        $userDb = fopen($path, 'a+');

        if (!$userDb) {
            return false;
        }
        if ($filePath && is_uploaded_file($filePath)) {
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

        $oldDb = "db/$userId.db";
        if (file_exists($oldDb)) {
            $oldUserDb = fopen($oldDb, "r");

            while (!feof($oldUserDb)) {
                $line = fgets($oldUserDb);
                fwrite($userDb, $line);
            }
            fclose($userDb);
        } else {
            $oldUserDb = fopen($oldDb, "a+");
            fclose($oldUserDb);
        }
        fclose($oldUserDb);

        rename($path, "db/$userId.db");
        return true;
    }


    public static function isUserExist($email)
    {
        $db = self::getInstance();
        while (!feof($db->connect)) {
            $line = fgets($db->connect);
            if ($line) {
                $line = json_decode($line, true);
                if ($email == $line['email']) {
                    return $line;
                }
            }
        }
        fseek($db->connect, 0);
        return false;
    }


    public static function addUser($email, $firstName, $lastName, $password)
    {
        $db = self::getInstance();
        $line = [
            'userId' => uniqid(),
            'email' => $email,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'password' => sha1($password)
        ];
        fwrite($db->connect, json_encode($line) . PHP_EOL);
        fseek($db->connect, 0);
        return $line;
    }


    public static function listPosts($userId, $postsPerPage, $page = 1)
    {
        $shift = ($page - 1) * $postsPerPage;
        $userPosts = [];
        $filePath = 'db/' . $userId . '.db';
        if (file_exists($filePath)) {
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
    }


    public static function checkUser($email, $password)
    {
        $password = sha1($password);
        $db = self::getInstance();
        $line = fgets($db->connect);
        if ($line) {
            $line = json_decode($line, true);
            if ($email == $line['email'] && $password == $line['password']) {
                fseek($db->connect, 0);
                return $line;
            }
        }
        fseek($db->connect, 0);
        return false;
    }


    public static function getPhotosByUser($userId, $imagesPerPage, $page = 1)
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

}

