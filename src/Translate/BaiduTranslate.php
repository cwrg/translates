<?php

namespace Cwrg\Translates\Translate;


class BaiduTranslate extends AbTranslate
{
    /**
     * @var string
     */
    protected $host = 'api.fanyi.baidu.com';

    /**
     * 翻译
     * @return string
     */
    public function translate($content = '')
    {
        $salt = rand(10000, 99999);
        $sign = $this->sign($salt, $content);
        $url = "http://{$this->host}/api/trans/vip/translate?q={$content}&appid={$this->config['appid']}&salt={$salt}&from={$this->source}&to={$this->target}&sign={$sign}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($ch);
        curl_close($ch);
        $sentencesArray = json_decode($result, true);
        if (!isset($sentencesArray['trans_result'])) {
            throw new \RuntimeException($result);
        }
        $sentences = "";
        foreach ($sentencesArray['trans_result'] as $v) {
            $sentences .= ucwords($v['dst']);
        }
        return $sentences;
    }

    /**
     * @param $salt
     * @param $content
     * @return string
     */
    private function sign($salt, $content)
    {
        return md5($this->config['appid'] . $content . $salt . $this->config['key']);
    }
}
