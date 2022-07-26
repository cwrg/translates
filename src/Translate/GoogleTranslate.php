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
    protected $host = 'https://translate.google.com';
    /**
     * @var GoogleTokenGenerator
     */
    protected $tokenProvider;
    /**
     * @var string|null Last detected source language
     */
    protected $lastDetectedSource;
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
        $response = $this->client->get('translate_a/single', [
            'query' => preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', http_build_query($query))
        ]);
        $result = $response->getBody()->getContents();
        $responseArray = json_decode($result, true);
        if (is_string($responseArray) && $responseArray != '') {
            $responseArray = [$responseArray];
        }
        // Check if translation exists
        if (empty($responseArray[0])) {
            return null;
        }
        // Detect languages
        $detectedLanguages = [];

        // the response contains only single translation, don't create loop that will end with
        // invalid foreach and warning
        if (!is_string($responseArray)) {
            foreach ($responseArray as $item) {
                if (is_string($item)) {
                    $detectedLanguages[] = $item;
                }
            }
        }

        // Another case of detected language
        if (isset($responseArray[count($responseArray) - 2][0][0])) {
            $detectedLanguages[] = $responseArray[count($responseArray) - 2][0][0];
        }
        // Set initial detected language to null
        $this->lastDetectedSource = null;

        // Iterate and set last detected language
        foreach ($detectedLanguages as $lang) {
            if ($this->isValidLocale($lang)) {
                $this->lastDetectedSource = $lang;
                break;
            }
        }

        // the response can be sometimes an translated string.
        if (is_string($responseArray)) {
            return $responseArray;
        } else {
            if (is_array($responseArray[0])) {
                return (string)array_reduce($responseArray[0], function ($carry, $item) {
                    $carry .= $item[0];
                    return $carry;
                });
            } else {
                return (string)$responseArray[0];
            }
        }
    }

    /**
     * Check if given locale is valid.
     *
     * @param string $lang Langauge code to verify
     * @return bool
     */
    protected function isValidLocale(string $lang)
    {
        return (bool)preg_match('/^([a-z]{2})(-[A-Z]{2})?$/', $lang);
    }
}
