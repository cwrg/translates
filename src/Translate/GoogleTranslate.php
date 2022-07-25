<?php

namespace Cwrg\Translates\Translate;


use Cwrg\Translates\Translate\Tokens\GoogleTokenGenerator;

/**
 * @mixin GoogleTranslate 谷歌翻译
 */
class GoogleTranslate extends AbTranslate
{
    /**
     * @var string
     */
    protected $host = 'https://translate.google.cn';
    /**
     * @var GoogleTokenGenerator
     */
    protected $tokenProvider;
    /**
     * @var array URL Parameters
     */
    protected $urlParams = [
        'client' => 'gtx',
        'hl' => 'en',
        'dt' => [
            't',   // Translate
            'bd',  // Full translate with synonym ($bodyArray[1])
            'at',  // Other translate ($bodyArray[5] - in google translate page this shows when click on translated word)
            'ex',  // Example part ($bodyArray[13])
            'ld',  // I don't know ($bodyArray[8])
            'md',  // Definition part with example ($bodyArray[12])
            'qca', // I don't know ($bodyArray[8])
            'rw',  // Read also part ($bodyArray[14])
            'rm',  // I don't know
            'ss'   // Full synonym ($bodyArray[11])
        ],
        'sl' => null, // Source language
        'tl' => null, // Target language
        'q' => null, // String to translate
        'ie' => 'UTF-8', // Input encoding
        'oe' => 'UTF-8', // Output encoding
        'multires' => 1,
        'otf' => 0,
        'pc' => 1,
        'trs' => 1,
        'ssel' => 0,
        'tsel' => 0,
        'kc' => 1,
        'tk' => null,
    ];

    /**
     * @param $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->tokenProvider = new GoogleTokenGenerator();
    }

    /**
     * 翻译
     * @param $text
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function translate($text = '')
    {
        $query = array_merge($this->urlParams, [
            'sl' => $this->source,
            'tl' => $this->target,
            'tk' => $this->tokenProvider->generateToken($this->source, $this->target, $text),
            'q' => $text
        ]);
        $response = $this->client->get('translate_a/single', ['query' => $query]);
        $result = $response->getBody()->getContents();
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
