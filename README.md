
QQRobot是一个轻量级,高扩展的QQ机器人的php开源库,

原先的github被封了,



批量发送功能慎用,小心封号

===============

> php的运行环境要求PHP7+。



> 他依赖coolq,如果你不熟悉coolq，我提供了[快捷安装](https://github.com/188700679/yjscript/blob/master/coolq%E5%AE%89%E8%A3%85%E6%95%99%E7%A8%8B.txt)

> 个人[实践代码](https://github.com/188700679/tp5_qqrobot)

> qqRobot的权限依赖QQ的权限，尽量你的qq机器人在群内或是其他地方有最高权限（管理员），避免某些群管理功能失效

# 安装
~~~
composer require 269995848/qqrobot dev-master
~~~

# 它

1.消息委托

2.消息代理

3.发送图片,文字

4.消息群发

5.发送音频

6.组件化开发

7.Q群管理,列表获取等

8.自动解析

9.默认异步发送

10.智能对话,图片检黄(内置组件)//图片检黄组件剔除，因为并发性太低了

11.事件监听


# HOW TO DO IT

#### 全局配置
~~~
 $config=[
    'proxyQQ'       =>'', //消息事件委托转发QQ
    'host'          =>'127.0.0.1:5700', //监听地址+端口,要配置内网地址,一般是ens0,某些服务器是ens33
    'cookies'       =>false,
    'httpOrS'       =>true,
    'isLog'         =>true, //是否开启日志,默认生成在类库 'qqrobot.log'
    'listenQQRobot' =>true, //是否监听
    'token'         =>'',
    'allowFriend'   =>true,  //是否允许加我为好友
    'allowGroup'    =>true,  //是否允许加我入群
    'atParse'       =>true,  //是否解析at我
    'logFile'       =>'',    //日志文件命名,需要有权限
    'self_qq'       =>'2442459484'  //机器人QQ号
];

$server=new Server($config);
~~~

#### 动态配置
> 他是局部配置,优先级比全局配置高,但只存在当前server实例中,应用场景不同

~~~
use QQRobot\Server;

$server=new Server();
$server->isLog=false;   //关闭日志记录
~~~

#### 接受事件消息

~~~
use QQRobot\Server;

$server=new Server();
$response=$server->response();
~~~


#### 对事件的产生者回应

> 对当前的事件者回应,必须依赖当前的server实例

~~~
use QQRobot\Server;
use QQRobot\QQRobotConst;
use QQRobot\Client;


$server=new Server();
$response=$server->response();

if(isset($response->notice_type)){
    if(
        $response->user_id!=$server->config->self_qq
        &&
        ($response->sub_type== QQRobotConst::APPROVE)
        &&
        ($response->notice_type==QQRobotConst::GROUP_INCREASE)
    ){
        $msg=<<<EOT
嘿嘿,你好,欢迎来到本群,我是本群小助手,请@我,并输入"帮助"
EOT;
        $client=new Client($response);  //开启clien实例
        $client->on('back',function()use($msg){   
            return [
                [
                'msg'=>$msg, //回应消息,(必填)
                'emoji'=>'128552',  //可以附带emoji,(不必)
                'at'=>'1234567',//是否@qq1234567,如果要@当前事件产生者,则'at'=>'at'.(不必)
                'img'=>'绝对路径.png',  //(不必)如果你安装是cqa,则不支持这个功能
                 'rec'=>'音频绝对路径' //不必 如果你安装是cqa,则不支持这个功能
                 ],
                 [
                 'msg'=>$msg, //回应消息,(必填)
                 'emoji'=>'128552',  //可以附带emoji,(不必)
                 'at'=>'1234567',//是否@qq1234567,如果要@当前事件产生者,则'at'=>'at'.(不必)
                 'img'=>'绝对路径.png',  //(不必)如果你安装是cqa,则不支持这个功能
                  'rec'=>'音频绝对路径' //不必 如果你安装是cqa,则不支持这个功能
                  ],
                 
             ]          
        });
    }
}
~~~


#### 组件化开发

> 组件化不支持动态配置

~~~
use QQRobot\Load;

$load=new  Load($config);  //($config 不必) 默认会按照当前server实例的配置进行读取
$load->addObserver(new Leave());
$load->addObserver(new Join());
$load->addObserver(new AtMe());
$load->loader();

~~~

#### 主动发送消息

> 主动发送消息事件,不依赖当前server实例

~~~
use QQRobot\Client;


$client=new Client();
$client->on('msg',function(){
    return
        ['msg'=>'你从哪里来?',  //(必填)
         'emoji'=>'128552',   //(不必)
         'group'=>true,    //(必填) true:发送群里,false:私聊某人
         'qq'=>'623582882',  //(必填) 群号/qq号
         'at'=>'623582882'  //(不必)是否@,私聊则无效
         'img'=>'绝对路径.png', //不必，如果你安装是cqa,则不支持这个功能
         'rec'=>'音频绝对路径' //不必，如果你安装是cqa,则不支持这个功能
     ]; 

});
~~~

#### 群发

~~~
 public function time(){
        $client=new Client($this->config);
        $msg="消息群发啦";

        $qun=['xxxx','xxxx','xxxx'];
        $emoji=rand(128512,128588);;
        $arr=[
            'msg'=>$msg,
            'emoji'=>$emoji,
            'group'=>'true',
            'qq'=>'',
            'img'=>'qqrobot_detail.png'
        ];

        $msg=[];

        foreach($qun as $v){
            $arr['qq']=$v;
            $msg[]=$arr;
        }

        $client->on('msg',function()use($msg){
            return $msg;
        });

    }

~~~

> server实例应在你的项目入口处调用,比如tp5默认是index/index/index



### 意思不意思是你的意思，我将会继续去维护下去，目前正在进行微信机器人开发
#### 微信
![Image text](http://139.224.101.36:81/wx.png)           
  
#### 支付宝                            
![Image text](http://139.224.101.36:81/zfb.png)


感谢捐赠者：
消逝，随心所欲，`andYes，忧愁的程序员，大致*如此，人生搬砖工


