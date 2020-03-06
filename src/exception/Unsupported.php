<?php
/**
 * User: 木鱼
 * Date: 2020/3/5 8:18
 * Ps:
 */

namespace QQRobot\exception;

include_once __DIR__.'/../helper.php';

class Unsupported extends TransferException{

    public function __construct($str) {
        if (!$str) {
            return null;
        }
        commonLog('',$str.' Temporarily not supported');
        trigger_error(
            $str.' Temporarily not supported',E_USER_WARNING);
    }
}