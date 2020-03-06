<?php
/**
 * User: 木鱼
 * Date: 2020/3/2 14:30
 * Ps:
 */
namespace QQRobot\Exception;

use Throwable;

if (interface_exists(Throwable::class)) {
    interface QQRobotException extends Throwable
    {
    }
} else {

    interface QQRobotException
    {
    }
}