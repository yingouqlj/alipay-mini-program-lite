<?php
/**
 * Created by PhpStorm.
 * User: yingouqlj
 * Date: 17/1/17
 * Time: 下午1:49
 */

namespace Yingou\AliMiniProgram;

class Config
{
    public $appId;
    public $secret;
    public $accessToken;
    const ACCESS_TOKEN_REDIS_KEY = 'ali_access_token';
    protected $tmpFile = 'ali_mini_program_token.tmp';

    public function __construct($config = null)
    {

        if ($config == null) {
            //   $config = CoreFactory::instance()->packageConfig('miniProgram');
        }
        if (isset($config['appId'])) {
            $this->appId = $config['appId'];
        }
        if (isset($config['secret'])) {
            $this->secret = $config['secret'];
        }
    }

    /**
     * 覆盖这个方法写取token的实现 ，比如redis，数据库
     * @return $token
     */
    public function getAccessToken()
    {
        if (!file_exists(sys_get_temp_dir() . $this->tmpFile)) {
            return null;
        }
        $data = json_decode(file_get_contents(sys_get_temp_dir() . $this->tmpFile), true);
        if ($data['expire'] > time()) {
            return $data['token'];
        }
        return null;
    }

    /**
     * 覆盖这个方法 存token，默认写临时文件
     * @param $token
     * @param int $expires
     * @return int
     */
    public function setAccessToken($token, $expires = 0)
    {
        return file_put_contents(sys_get_temp_dir() . $this->tmpFile, json_encode(['token' => $token, 'expire' => (time() + $expires)]));
    }


}