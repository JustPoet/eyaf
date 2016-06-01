<?php
namespace Security;

use Yaf\Session;

/**
 * Class CSRF
 *
 * 防止CSRF攻击
 *
 * @package Security
 */
class CSRF
{
    /**
     * 需要校验的请求方式
     *
     * @var string[]
     */
    protected static $unsafe_methods = ["POST", "PUT", "PATCH", "DELETE"];

    /**
     * 初始化
     *
     * @throws Exception
     */
    public static function init()
    {
        //生成Token,并插入到session中
        static::generateToken();

        //校验Token
        static::checkCSRF();

        //写入Token信息到,输出缓存
        ob_start(static::generateCallback(static::getToken()));
    }

    /**
     * 生成Token,并插入到session中
     *
     * @return void
     */
    protected static function generateToken()
    {
        $session = Session::getInstance();
        $token = $session->get('csrf_token');
        if (isset($token) === false) {
            $session->set('csrf_token', bin2hex(openssl_random_pseudo_bytes(16)));
        }
    }

    /**
     * 获取session中的Token
     *
     * @return string
     */
    protected static function getToken()
    {
        $session = Session::getInstance();

        return $session->get('csrf_token');
    }

    /**
     * 生成csrf_token,到表单中
     *
     * @param string $token
     *
     * @return callable
     */
    protected static function generateCallback($token)
    {
        return function ($page) use ($token) {
            $tokenField = "\n<input type=hidden name=csrf_token value=$token>\n";
            $tokenJS = "\n<script>var CSRFTOKEN = '$token';</script>\n";
            if (strpos(strtolower($page), "<head>") !== false) {
                $page = substr_replace($page, "<head>" . $tokenJS, strpos(strtolower($page), "<head>"), 6);
            }
            $matches = [];
            if (preg_match_all('/<\s*\w*\s*form.*?>/is', $page, $matches, PREG_OFFSET_CAPTURE) !== 0) {
                foreach ($matches[0] as $match) {
                    $formOpen = strpos($page, $match[0], $match[1]);
                    $formClose = strpos($page, ">", $formOpen);
                    $formTag = substr($page, $formOpen, $formClose - $formOpen);
                    $formIsMethodGet = stripos(str_replace(['"', "'"], ["", ""], $formTag), "method=get") !== false;
                    if ($formIsMethodGet !== true) {
                        $page = substr_replace($page, $tokenField, $formClose + 1, 0);
                    }
                }
            }

            return $page;
        };
    }

    /**
     * 校验
     *
     * @throws Exception
     */
    protected static function checkCSRF()
    {
        $session = Session::getInstance();
        $token = $session->get('csrf_token');

        if (isset($token) === false) {
            print '非法请求';
            exit();
        }
        if (in_array($_SERVER['REQUEST_METHOD'], static::$unsafe_methods) === true) {
            $requestArguments = [];
            parse_str(file_get_contents('php://input'), $requestArguments);
            $requestArguments = array_merge($_POST, $requestArguments);
            if (array_key_exists("csrf_token", $requestArguments) === false
                || $requestArguments['csrf_token'] !== static::getToken()
            ) {
                if (headers_sent() === false) {
                    header("HTTP/1.0 403 Forbidden");
                }
                print '非法请求';
                exit();
            }
        }
    }
}