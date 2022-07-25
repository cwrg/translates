<?php

namespace Cwrg\Translates\Translate;

use GuzzleHttp\Client;

/**
 * @mixin AbTranslate
 */
abstract class AbTranslate
{
    /**
     * @var array 配置
     */
    protected $config;
    /**
     * @var string 请求域名
     */
    protected $host;
    /**
     * @var string 目标语言
     */
    protected $target;
    /**
     * @var string 源语言
     */
    protected $source;
    /**
     * @var Client 请求客户端
     */
    protected $client;

    /**
     * 初始化
     * @param $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => isset($this->config['host']) ? $this->config['host'] : $this->host,
            'verify' => false
        ]);
    }

    /**
     * 翻译
     * @return mixed
     */
    abstract protected function translate($text = '');

    /**
     * 目标语言
     * @param $target
     * @return $this
     */
    public function target($target)
    {
        $this->target = $target;
        return $this;
    }

    /**
     * 源语言
     * @param $source
     * @return $this
     */
    public function source($source)
    {
        $this->source = $source;
        return $this;
    }

}
