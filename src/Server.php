<?php
/**
 * User: 木鱼
 * Date: 2020/3/1 5:12
 * Ps:
 */

namespace QQRobot;


use QQRobot\Cookie\CookieJar;
use QQRobot\exception\BadResponseException;

include_once __DIR__.'/helper.php';

class Server extends ServerDefinition{

    /**
     * @var bool 监听
     */
    public $listenQQRobot;
    /**
     * @var 消息委托
     */
    private $proxyQQ;
    /**
     * @var 消息存放
     */
    public $msg=[];
    /**
     * @var 数据格式
     */
    public $json;
    /**
     * @var @QQ解析
     */
    public $atParse;
    /**
     * @var 消息出入监听
     */
    public $or;


    public $host;
    /**
     * @var 响应
     */
    public $accept;
    /**
     * @var array 接受队列
     */
    public $queue=[];

    /**
     * @var bool 服务端状态
     */
    public $status=false;
    /**
     * @var http or https
     */
    public $httpOrs;

    /**
     * @var http服务还是socket
     */
    public $http;

    /**
     * @var string
     * 日志生成文件
     */
    public $logFile;
    /**
     * @var array 业务逻辑队列
     */
    public $obs=[];
    /**
     * @var bool 允许加我好友
     */
    public $allowFriend;
    /**
     * @var bool 允许拉我进群
     */
    public $allowGroup;
    /**
     * @var bool
     * 是否记录
     */
    public $isLog;
    public $config=null;
    /**
     * @var 消息事件场景
     */
    private $type;

    public function __construct($config=[]){

        $this->type=[
            "message",
            "notice",
            "request"
        ];

        $this->configureDefaults($config);
    }


    private function configureDefaults(array $config){

        $defaults=[
            'http_errors'   =>true,
            'decode_content'=>true,
            'verify'        =>true,
            'cookies'       =>false,
            'httpOrS'       =>true,
            'proxyQQ'       =>'',
            'host'          =>'192.168.1.7:5700',
            'isLog'         =>true,
            'listenQQRobot' =>true,
            'token'         =>'',
            'allowFriend'   =>true,
            'allowGroup'    =>true,
            'atParse'       =>true,
            'logFile'       =>'',
            'self_qq'       =>'2442459484'
        ];

        // idn_to_ascii() 在某些环境可能失效
        $defaults['idn_conversion']=function_exists('idn_to_ascii') && (defined('INTL_IDNA_VARIANT_UTS46') || PHP_VERSION_ID<70200);

        //如果已设置，请使用标准的HTTP_代理或HTTPS_代理
        if(php_sapi_name()==='cli' && getenv('HTTP_PROXY')){
            $defaults['proxy']['http']=getenv('HTTP_PROXY');
        }

        if($proxy=getenv('HTTPS_PROXY')){
            $defaults['proxy']['https']=$proxy;
        }

        if($noProxy=getenv('NO_PROXY')){
            $cleanedNoProxy=str_replace(' ','',$noProxy);
            $defaults['proxy']['no']=explode(',',$cleanedNoProxy);
        }

        if($this->listenQQRobot!==null?$this->listenQQRobot:!$defaults['listenQQRobot']){
            return null;
        }

        $config=$config+$defaults;
        $config['request_time']=$_SERVER['REQUEST_TIME'];

        if(!empty($config['cookies']) && $config['cookies']===true){
            $config['cookies']=new CookieJar();
        }



        // 添加默认的代理投.
        if(!isset($config['headers'])){
            $config['headers']=['User-Agent'=>default_user_agent()];
        }else{
            // 没设置就添加代理投.
            foreach(array_keys($config['headers']) as $name){
                if(strtolower($name)==='user-agent'){
                    return;
                }
            }
            $config['headers']['User-Agent']=default_user_agent()();
        }


        //变更全局配置
        $this->config=setConfig($config);
        $this->listenQQRobot!==null?$this->listenQQRobot:$this->listenQQRobot=$this->config->listenQQRobot;


    }

    /**
     * @return mixed|void
     * user:木鱼  2019/12/25 2:19
     */
    public function dataHandle(){
        $this->parseIt();
    }



    public  function receive(){
        return $this;
    }

    /**
     * @return mixed|void
     * user:木鱼  2019/12/25 2:19
     */
    public function returnMessage(){}



    /**
     * @return mixed|void
     * user:木鱼  2019/12/25 2:19
     */
    public function parseIt(){
       if($this->accept->post_type!='message'){
           return null;
       }
        $accept=$this->accept->message;
        if(preg_match('/\[CQ:at,qq=(\d+)\]/',$accept,$qq)){
            $accept=str_replace($qq[0],'',$accept);
            $this->accept->message=$accept;
            $this->accept->isAt=true;
            $this->accept->atWho=$qq[1];
        }

    }


    public function __destruct(){
        if(!$this->listenQQRobot)return $this;
        if(!$this->accept)throw new BadResponseException();

        if(!isset($this->accept->post_type) || !in_array($this->accept->post_type,$this->type)){


            return null;
        }


        $this->accept->status=true;
        $this->host!==null?
            $this->host:
            $this->accept->host=$this->config->host;
        if($this->atParse!==null?$this->atParse:$this->config->atParse)
            $this->parseIt();

        $this->logOutput();
    }

    public function logOutput(){

        if($this->isLog!==null?$this->isLog:$this->config->isLog){
            commonLog($this->logFile!==null?$this->logFile:$this->config->logFile,$this->accept);
        }


    }

    /**
     * @return mixed|void
     * user:木鱼  2019/12/25 2:19
     */
    public function outAndIn($or){
        $this->or=$or;
    }

    /**
     * @return mixed|void
     * user:木鱼  2019/12/25 2:19
     */
    protected function makeStu($stu){
        $this->stu=$stu;
    }

    /**
     * @return mixed|void
     * user:木鱼  2019/12/25 2:19
     */
    protected function tryDoIt($data){
        $this->data=$data;
    }

    /**
     * @return mixed|void
     * user:木鱼  2019/12/25 2:19
     */
    protected function setListen(){
        $this->listen=true;
    }

    /**
     * @return mixed|void
     * user:木鱼  2019/12/25 2:19
     */
    protected function checkIt($msg){
        $this->msg=$msg;
    }

    /**
     * @return mixed
     * user:木鱼  2019/12/25 2:27
     */
    protected function accept(){
        return json_decode(file_get_contents('php://input',true));
    }
}