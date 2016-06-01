<?php
use Yaf\Controller_Abstract;
use Service\UserService;

/**
 * Class IndexController
 */
class IndexController extends Controller_Abstract
{
    /**
     * 分页
     */
    use Paginator;

    public function indexAction()
    {
        $userService = UserService::getInstance();
        $users = $userService->getUserList();
        $this->getView()->assign("users", $users);
        return true;
    }

    public function getUsersAction()
    {
        $users = $this->paginate(UserModel::orderBy('updated_at','desc'));
        $this->getView()->assign("users", $users);
        return true;
    }

    public function helloAction()
    {
        $this->getView()->assign("msg", 'hello');
        return true;
    }
}
