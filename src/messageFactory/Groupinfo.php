<?php
/**
 * User: 木鱼
 * Date: 2020/3/8 4:34
 * Ps:
 */

namespace QQRobot\messageFactory;


use QQRobot\exception\ParamsWrongException;

class Groupinfo implements TypeFactory{

    public static function typeHandle($param,$server){

        if(!isset($param['group_id'])){
            throw (new ParamsWrongException())->custom_error('group_id must be ');
        }

        $group_id=(int)$param['group_id'];
        if(!$group_id){
            throw (new ParamsWrongException())->custom_error('group_id must be int');
        }


        return $group_id;

    }


}