<?php

namespace Cwrg\Translates;

use Cwrg\Translates\Translate\BaiduTranslate;
use Cwrg\Translates\Translate\GoogleTranslate;

class Translate
{
    /**
     * 调用百度翻译
     * @param $config
     * @return BaiduTranslate
     */
    public static function baidu($config = [])
    {
        return new BaiduTranslate($config);
    }

    /**
     * 调用谷歌翻译
     * @param $config
     * @return GoogleTranslate
     */
    public static function google($config = [])
    {
        return new GoogleTranslate($config);
    }
}
