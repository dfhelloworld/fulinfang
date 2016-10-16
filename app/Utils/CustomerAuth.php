<?php
use App\UserTable;

class UtilsCustomerAuth
{


    /**
     * Check the username and password
     *
     * @param array [username, password] $authDetails
     * @return bool
     */
    public static function check($authDetails)
    {
        $password = md5($authDetails['password']);
        $user = DB::connection('mysql_user')->select("
          SELECT * FROM ky_member WHERE member_name = ? OR member_email = ? LIMIT 1
          ", [$authDetails['username'], $authDetails['username']]);
        if (count($user) > 0 && $user[0]->member_passwd == $password) {
            return $user[0];
        } else {
            return false;
        }
    }

    /**
     * Set session with user array
     *
     * @param array $user
     */
    public static function login($user)
    {
        session_start();
        $_SESSION['user_id'] = $user->member_id;
        $_SESSION['user_name'] = $user->member_name;
        $_SESSION['user_email'] = $user->member_email;
    }

    /**
     * Logout and destroy session
     */
    public static function logout()
    {
        session_start();
        if ($_SESSION['user_id']) {
            $_SESSION = array();
            session_destroy();
        }
    }

    public static function getUserId()
    {
        session_start();
        if (isset($_SESSION['user_id'])) {
            return intval($_SESSION['user_id']);
        }
    }
}