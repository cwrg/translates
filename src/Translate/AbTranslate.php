<?php

namespace Cwrg\Translates\Translate;

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
     * 初始化
     * @param $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;
        isset($this->config['host']) && $this->host = $this->config['host'];
    }

    /**
     * 翻译
     * @return mixed
     */
    abstract protected function translate($content = '');

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

    /**
     * 翻译文字
     * @param $text
     * @return $this
     */
    public function text($text = '')
    {
        $this->text = $text;
        return $this;
    }
}
