<?php

namespace Cwrg\Translates\Translate;

/**
 * @mixin BaiduTranslate 百度翻译
 */
class BaiduTranslate extends AbTranslate
{
    /**
     * @var string
     */
    protected $host = 'http://api.fanyi.baidu.com';

    /**
     * 翻译
     * @param $text
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function translate($text = '')
    {
        $response = $this->client->get('/api/trans/vip/translate', [
            'query' => [
                'q' => $text,
                'appid' => $this->config['appid'],
                'salt' => $salt = rand(10000, 99999),
                'from' => $this->source,
                'to' => $this->target,
                'sign' => $this->sign($salt, $text)
            ]
        ]);
        $result = $response->getBody()->getContents();
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
