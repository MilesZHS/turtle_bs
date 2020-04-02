<?php


namespace app\common\lib;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use think\Cache;
use think\Log;

class Alidayu
{
    /**
     * @var null
     * 静态变量保存一个全局的实例
     */
    private static $_Instance = null;

    /**
     * Alidayu constructor.
     * 私有的构造方法
     */
    private function __construct()
    {
    }

    /**
     * 静态方法，单例模式的统一入口
     */
    public static function getInstance(){
        if(is_null(self::$_Instance)){
            self::$_Instance = new self();
        }
        return self::$_Instance;
    }

    public function sendSms($phone = ''){
        AlibabaCloud::accessKeyClient(config('alidayu.accessKeyId'), config('alidayu.accessKeySecret'))
            ->regionId('cn-qingdao')
            ->asDefaultClient();

        $code = rand(100000,999999);//生成随机验证码
        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-qingdao",
                        'PhoneNumbers' => $phone,
                        'SignName' => config('alidayu.SignName'),
                        'TemplateCode' => config('alidayu.TemplateCode'),
                        'TemplateParam' => "{\"code\":\"".$code."\"}",
                    ],
                ])
                ->request();
            if ($result->Message == 'OK'){
                //设置验证码失效时间
                Cache::set($phone,$code,config('alidayu.identify_time'));
                return true;
            }
            Log::write("alidayu-----",json_encode($result));
            return false;
//            print_r($result->toArray());
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }
    }

    /**
     * 根据手机号码查询验证码是否正常
     */
    public function checkSmsIdentify($phone = ''){
        if (!$phone){
            return false;
        }
        return Cache::get($phone);
    }

}