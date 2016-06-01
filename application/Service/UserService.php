<?php
namespace Service;

/**
 * Class UserService
 *
 * @package Service
 */
class UserService
{
    public function getUserList()
    {
        $userList = \UserModel::all();
        return $userList;
    }

    //使用单例
    use \Singleton;
}