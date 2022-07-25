<?php

namespace Cwrg\Translates\Translate;


class GoogleTranslate extends AbTranslate
{
    /**
     * @var string
     */
    protected $host = 'translate.google.cn';

    /**
     * 翻译
     * @return string
     */
    public function translate($content = '')
    {
        $url = "https://{$this->host}/translate_a/single?client=at&dt=t&dt=ld&dt=qca&dt=rm&dt=bd&dj=1&hl=es-ES&ie=UTF-8&oe=UTF-8&inputm=2&otf=2&iid=1dd3b944-fa62-4b55-b330-74909a99969e";
        $fields = array('sl' => urlencode($this->source), 'tl' => urlencode($this->target), 'q' => urlencode($content));

        // URL-ify the data for the POST
        $fields_string = "";
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim($fields_string, '&'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'AndroidTranslate/5.3.0.RC02.130475354-53000263 5.1 phone TRANSLATE_OPM5_TEST_1');

        // Execute post
        $result = curl_exec($ch);
        // Close connection
        curl_close($ch);

        $sentencesArray = json_decode($result, true);
        $sentences = "";
        if (!isset($sentencesArray["sentences"])) {
            throw new \RuntimeException($result);
        }
        foreach ($sentencesArray["sentences"] as $s) {
            $sentences .= isset($s["trans"]) ? $s["trans"] : '';
        }
        return $sentences;
    }
}
