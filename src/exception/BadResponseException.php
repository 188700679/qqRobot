<?php
/**
 * User: 木鱼
 * Date: 2020/3/2 14:28
 * Ps:
 */

namespace QQRobot\exception;


include_once __DIR__.'/../helper.php';
class BadResponseException extends TransferException{

    public function __construct() {

        commonLog('',' Instantiating the ' . __CLASS__ . ' class without a Response is deprecated');
        trigger_error(
            ' Instantiating the ' . __CLASS__ . ' class without a Response is deprecated',
            E_USER_DEPRECATED
        );
    }
}