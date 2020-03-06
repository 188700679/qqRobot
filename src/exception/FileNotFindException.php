<?php
/**
 * User: 木鱼
 * Date: 2020/3/6 19:54
 * Ps:
 */

namespace QQRobot\exception;


class FileNotFindException  extends TransferException{

    public function __construct($str=null) {

        if (is_string($str)) {
            commonLog($str.' is not find');
            trigger_error($str.' is not find',E_USER_WARNING);
        }
    }
}