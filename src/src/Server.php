<?php
/**
 * User: 木鱼
 * Date: 2020/3/1 5:12
 * Ps:
 */

namespace QQRobot;



use QQRobot\Cookie\CookieJar;

class Server extends Event{

    private $config;

    /**
     * @var 是否代理监听
     */
    public $listen=true;


    /**
     * @var 消息代理
     */
    public $proxy=false;

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
    public $atParse=true;

    /**
     * @var 消息出入监听
     */
    public $or;

    /**
     * @var 响应
     */
    public $accept;

    /**
     * @var array 接受队列
     */
    public $queue=[];

    /**
     * @var array 业务逻辑队列
     */
    public $obs=[];


    /**
     * @var bool
     * 是否记录
     */
    public $isLog=true;

    public function __construct($config=[]){
        $this->configureDefaults($config);
    }




    private function configureDefaults(array $config)
    {
        $defaults = [
            'http_errors'     => true,
            'decode_content'  => true,
            'verify'          => true,
            'cookies'         => false
        ];

        // idn_to_ascii() 在某些环境可能失效
        $defaults['idn_conversion'] = function_exists('idn_to_ascii')
            && (
                defined('INTL_IDNA_VARIANT_UTS46')
                ||
                PHP_VERSION_ID < 70200
            );

        //如果已设置，请使用标准的HTTP_代理或HTTPS_代理
        if (php_sapi_name() === 'cli' && getenv('HTTP_PROXY')) {
            $defaults['proxy']['http'] = getenv('HTTP_PROXY');
        }

        if ($proxy = getenv('HTTPS_PROXY')) {
            $defaults['proxy']['https'] = $proxy;
        }

        if ($noProxy = getenv('NO_PROXY')) {
            $cleanedNoProxy = str_replace(' ', '', $noProxy);
            $defaults['proxy']['no'] = explode(',', $cleanedNoProxy);
        }

        $this->config = $config + $defaults;

        if (!empty($config['cookies']) && $config['cookies'] === true) {
            $this->config['cookies'] = new CookieJar();
        }

        // 添加默认的代理投.
        if (!isset($this->config['headers'])) {
            $this->config['headers'] = ['User-Agent' => Helper::default_user_agent()];
        } else {
            // 没设置就添加代理投.
            foreach (array_keys($this->config['headers']) as $name) {
                if (strtolower($name) === 'user-agent') {
                    return;
                }
            }
            $this->config['headers']['User-Agent'] = Helper::default_user_agent()();
        }
    }


    /**
     * @return mixed|void
     * user:木鱼  2019/12/25 2:19
     */
    public function dataHandle(){}


    /**
     * @param $listen
     * user:木鱼  2020/3/1 11:06
     * 监听
     */
    public function isListen($listen){
        parent::isListen($listen); // TODO: Change the autogenerated stub
    }


    /**
     * @return mixed|void
     * user:木鱼  2019/12/25 2:19
     */
    public  function returnMessage(){}

    /**
     * @return mixed|void
     * user:木鱼  2019/12/25 2:19
     */
    protected function makeStu($stu)
    {
        $this->stu=$stu;
    }


    public function __destruct(){
        if($this->atParse) $this->parseIt();
        $this->logOutput();



    }
    /**
     * @return mixed|void
     * user:木鱼  2019/12/25 2:19
     */
    protected function tryDoIt($data)
    {
        $this->data=$data;
    }

    /**
     * @return mixed|void
     * user:木鱼  2019/12/25 2:19
     */
    protected function listen()
    {
        $this->listen=[];
    }


    /**
     * @return mixed|void
     * user:木鱼  2019/12/25 2:19
     */
    protected function checkIt($msg)
    {
        $this->msg=$msg;
    }


    /**
     * @return mixed|void
     * user:木鱼  2019/12/25 2:19
     */
    protected function parseIt(){

        $accept=json_encode($this->accept,JSON_UNESCAPED_UNICODE );
        if(preg_match('/\[CQ:at,qq=(\d+)\]/', $accept, $qq)){
            $accept=str_replace($qq[0],'',$accept);
            $this->accept=json_decode($accept);
            $this->accept->isAt=true;
            $this->accept->atWho=$qq[1];
        }


    }


    public function logOutput(){
        if($this->isLog){
            file_put_contents(__dir__."/qqrobot.log",var_export($this->accept,true),FILE_APPEND);
        }

    }

    /**
     * @return mixed|void
     * user:木鱼  2019/12/25 2:19
     */
    public function outAndIn($or)
    {
        $this->or=$or;
    }



    /**
     * @return mixed
     * user:木鱼  2019/12/25 2:27
     */
    protected function accept(){
        return file_get_contents('php://input',true);

    }
}