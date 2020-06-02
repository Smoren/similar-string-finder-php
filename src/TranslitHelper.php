<?php


namespace Smoren\SimilarStringFinder;


use dastanaron\translit\Translit;

class TranslitHelper
{
    protected static $instance;

    /**
     * @var Translit
     */
    protected $transliterator;

    /**
     * @param string $input
     * @return string
     */
    public static function translit(string $input, string $convertParam = 'ru-en'): string
    {
        /** @var TranslitHelper $inst */
        $inst = static::gi();
        return $inst->transliterator->translit($input, true, $convertParam);
    }

    /**
     * @param string $input
     * @return string
     */
    public static function translitWithSpace(string $input, string $convertParam = 'ru-en'): string
    {
        /** @var TranslitHelper $inst */
        $inst = static::gi();
        return $inst->transliterator->translit($input, false, $convertParam);
    }

    public static function translitUpper(string $input, string $convertParam = 'ru-en'): string
    {
        return mb_strtoupper(static::translit($input, $convertParam));
    }

    /**
     * @return TranslitHelper
     */
    protected static function gi(): self
    {
        if(static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * TranslitHelper constructor.
     */
    protected function __construct()
    {
        $this->transliterator = new Translit();
    }
}