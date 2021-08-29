<?php

declare(strict_types=1);

namespace App\Common\Api;

/**
 * Class Status
 * @package App\Common\Api
 */
class Status
{
    /**
     * API状态：Success
     */
    const SUCCESS = '20000|Success';

    const UNAUTHORIZED = '40001|Unauthorized';
    const UNFOUND = '40400|Unfound';

    /**
     * API状态：HTTP状态码
     * 状态码范围：40000-59999
     * 说明：当放生HTTP错误，即HTTP状态码为`400~599`时，系统将HTTP状态码末尾补齐`00`作为API状态码
     * 例：
     * 401 => 40100
     * 404 => 40400
     * 500 => 50000
     */

    /**
     * API状态：System Error
     * 状态码范围：60000-60999
     * 默认：60000
     */
    const ERR_SYS = '60000|系统错误';

    /**
     * API状态：Auth Error
     * 状态码范围：61000-61999
     * 默认：61000
     */
    const ERR_AUTH = '61000|认证操作失败';

    /**
     * API状态：User Error
     * 状态码范围：62000-62999
     * 默认：62000
     */
    //***菜单***
    const ERR_USER = '62000|用户操作失败';


    /**
     * API状态： Product Error
     * 状态码范围：63000-63999
     * 默认：63000
     */
    const ERR_PRODUCT = '63000|商品操作失败';

    /**
     * API状态：Order Error
     * 状态码范围：64000-64999
     * 默认：64000
     */
    const ERR_ORDER = '63000|订单操作失败';

    /**
     * API状态：Pay Error
     * 状态码范围：65000-65999
     * 默认：65000
     */
    const ERR_PAY = '65000|支付操作失败';

    /**
     * API状态：Finance Error
     * 状态码范围：66000-66999
     * 默认：66000
     */
    const ERR_FINANCE = '66005|财务操作失败';

    const ERR_SYSTEM = '50000|系统出错';

    const ERR_PARAM_VALIDAE= '80000|参数有误';


    /**
     * @var int $code
     */
    private $code;

    /**
     * @var string $msg
     */
    private $msg;

    /**
     * Status constructor.
     * @param string $statusStr
     * @param string $customMsg
     */
    public function __construct(string $statusStr, string $customMsg = '')
    {
        $this->code = $this->parseCode($statusStr);
        $this->msg = $this->parseMsg($statusStr, $customMsg);
    }

    /**
     * 获取状态码
     *
     * @param string $statusStr
     * @return int
     */
    private function parseCode(string $statusStr)
    {
        $code = strstr($statusStr, '|', true);
        if (empty($code) || !is_numeric($code)) {
            // 若状态码不合法，则返回默认状态码
            return (int)strstr(self::ERR_SYS, '|', true);
        }

        return (int)$code;
    }

    /**
     * 获取状态消息
     *
     * @param string $statusStr
     * @param string $msg
     * @return string
     */
    private function parseMsg(string $statusStr, string $msg = '')
    {
        // 若自定义msg不为空，则返回自定义msg
        if (!empty($msg)) {
            return __($msg);
        }

        $msg = strstr($statusStr, '|', false);
        if ($msg === false) {
            // 状态格式不规范，则返回默认系统错误消息
            return (string)__(ltrim(strstr(self::ERR_SYS, '|', false), '|'));
        }
        $msg = ltrim($msg, '|');
        if (empty($msg)) {
            // 状态消息为空，则返回默认系统错误消息
            return (string)__(ltrim(strstr(self::ERR_SYS, '|', false), '|'));
        }

        // 返回已定义的的状态消息
        return (string)__($msg);
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }
}
