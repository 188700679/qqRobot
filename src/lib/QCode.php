<?php
/**
 * User: 木鱼
 * Date: 2019/12/29 23:21
 * Ps:
 */

namespace QQRobot\lib;


class QCode{
    public static function CQ($type, $argArray = NULL){
        $code='[CQ:'.$type;
        if(NULL !== $argArray) foreach($argArray as $key => $value){
            $code.= (','.$key.'='.self::EncodeCQCode($value));
        }
        $code.=']';
        return $code;
    }

    public static function At($qq){
        return self::CQ('at', ['qq' => $qq]);
    }

    public static function Face($id){
        return self::CQ('face', ['id' => $id]);
    }

    public static function BFace($id){
        return self::CQ('bface', ['id' => $id]);
    }

    public static function SFace($id){
        return self::CQ('sface', ['id' => $id]);
    }

    public static function Emoji($id){
        return self::CQ('emoji', ['id' => $id]);
    }

    public static function Image($file){
        return self::CQ('image', ['file' => $file]);
    }

    public static function Record($file){
        return self::CQ('record', ['file' => $file]);
    }

    public static function Rps($type){
        return self::CQ('rps', ['type' => $type]);
    }

    public static function Dice($type){
        return self::CQ('dice', ['type' => $type]);
    }

    public static function Shake(){
        return self::CQ('shake');
    }

    public static function Anonymous($ignore){
        return $ignore?self::CQ('anonymous', ['ignore' => 'true']):self::CQ('anonymous');
    }

    public static function Music($type, $id){
        return self::CQ('music', [
            'type' => $type,
            'id' => $id,
        ]);
    }

    public static function CustomMusic($audio, $url, $title, $content, $image){
        return self::CQ('music', [
            'type' => 'custom',
            'audio' => $audio,
            'url' => $url,
            'title' => $title,
            'content' => $content,
            'image' => $image
        ]);
    }

    public static function Share($url, $title, $content, $image){
        return self::CQ('share', [
            'url' => $url,
            'title' => $title,
            'content' => $content,
            'image' => $image,
        ]);
    }

    public static function EncodeCQCode($str){
        return str_replace([
            '&',
            '[',
            ']',
            ',',
        ], [
            '&amp;',
            '&#91;',
            '&#93;',
            '&#44;',
        ], $str);
    }

    public static function DecodeCQCode($str){
        return str_replace([
            '&amp;',
            '&#91;',
            '&#93;',
            '&#44;',
        ],[
            '&',
            '[',
            ']',
            ',',
        ], $str);
    }
}