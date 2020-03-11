<?php
/**
 * User: 木鱼
 * Date: 2019/12/30 0:30
 * Ps:
 */

namespace QQRobot\lib;


interface Definition{

    public const send_private_msg = '/send_private_msg';
    public const send_private_msg_async = '/send_private_msg_async';
    public const send_group_msg = '/send_group_msg';
    public const send_group_msg_async = '/send_group_msg_async';
    public const send_discuss_msg = '/send_discuss_msg';
    public const send_discuss_msg_async = '/send_discuss_msg_async';
    public const send_msg = '/send_msg';
    public const send_msg_async = '/send_msg_async';
    public const set_group_add_request = '/set_group_add_request';
    public const get_group_info = '/_get_group_info';

    public const set_friend_add_request = '/set_friend_add_request';

}