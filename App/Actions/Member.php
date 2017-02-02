<?php
namespace Actions;

use Library\DBHelper;
use Library\Presentation;

class Member extends Presentation {
    public static $table = 'users';

    public static function checkLogin()
    {
        if (!self::isLogin()) {
            parent::redirect('index.php?action=login');
        }
    }

    /**
     * Check login status
     */
    public static function isLogin()
    {
        if (!isset($_SESSION['convert_member'])) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check login for member
     *
     * @param $username
     * @param $password
     * @return bool
     */
    public static function login($username, $password)
    {
        if (!empty($username) && !empty($password)) {
            $username = addslashes(trim($username));
            $password = trim(sha1($password));
            // Check user:
            $user = DBHelper::getRow('*', self::$table, "username = '$username' and password = '$password' and status = 1");

            if ($user) {
                // Set session
                $_SESSION['convert_member'] = $user;

                return true;
            }
        }

        return false;
    }

    /**
     * Check username exist
     *
     * @param $username
     * @return bool
     */
    public static function usernameExist($username)
    {
        $username = addslashes(trim($username));

        $user = DBHelper::getRow('*', self::$table, "username = '$username'");

        if ($user) {
            return true;
        }

        return false;
    }

    /**
     * Create user
     *
     * @param $username
     * @param $password
     * @param int $type
     * @param int $status
     * @return bool|mixed
     */
    public static function createUser($username, $password, $type = 0, $status = 1)
    {
        if (!empty($username) && !empty($password) && !self::usernameExist($username)) {
            $username = addslashes(trim($username));
            $password = sha1($password);

            $userId = DBHelper::Insert(self::$table, [
                'username' => $username,
                'password' => $password,
                'type'     => (int)$type,
                'status'   => (int)$status
            ]);
            return $userId;
        }

        return false;
    }

    /**
     * Get user by id
     *
     * @param $userId
     * @return array|bool
     */
    public static function getById($userId)
    {
        $user = DBHelper::getRow('*', self::$table, "id = " . (int)$userId);

        if ($user) {
            return $user;
        }

        return false;
    }

    /**
     * Update password for user
     *
     * @param $userId
     * @param $password
     * @return bool|\mysqli_result
     */
    public static function updatePassword($userId, $password)
    {
        return DBHelper::Update(self::$table, ['password' => sha1($password)], 'id = ' . (int)$userId);
    }

    /**
     * Update user
     *
     * @param $userId
     * @param $updateArray
     * @return bool|\mysqli_result
     */
    public static function updateInfo($userId, $updateArray)
    {
        return DBHelper::Update(self::$table, $updateArray, 'id = ' . (int)$userId);
    }
}