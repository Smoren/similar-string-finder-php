<?php


namespace Smoren\SimilarStringFinder;

/**
 * Класс, реализующий поиск индекса в массиве списков строк
 * @package Smoren\SimilarStringFinder
 * @author Smoren <ofigate@gmail.com>
 */
class SimilarStringFinder
{
    /**
     * @var array
     */
    protected $storage;

    /**
     * SimilarStringFinder constructor.
     */
    public function __construct()
    {
        $this->storage = [];
    }

    /**
     * @param string $input
     * @return int|string|null
     */
    public function find(string $input)
    {
        $input = mb_strtolower($input);
        $inputTranslit = TranslitHelper::translit($input);

        $bestId = null;
        $bestSimilarityIndex = -1;
        foreach($this->storage as $operatorId => $operatorStrings) {
            foreach($operatorStrings as $operatorString) {
                $similarityIndex = max(
                    $this->compareStrings($input, $operatorString),
                    $this->compareStrings($inputTranslit, $operatorString)
                );
                if($similarityIndex > $bestSimilarityIndex) {
                    $bestSimilarityIndex = $similarityIndex;
                    $bestId = $operatorId;
                }
            }
        }

        return $bestId;
    }

    /**
     * @param $index
     * @param array $strings
     * @return $this
     */
    public function addStrings($index, array $strings): self
    {
        if(!isset($this->storage[$index])) {
            $this->storage[$index] = [];
        }

        $dest = &$this->storage[$index];

        foreach($strings as $str) {
            $str = mb_strtolower($str);

            $dest[] = $str;
            $dest[] = TranslitHelper::translit($str);
        }

        return $this;
    }

    /**
     * @param string $lhs
     * @param string $rhs
     * @return float
     */
    protected function compareStrings(string $lhs, string $rhs): float
    {
        return similar_text($lhs, $rhs)/max(strlen($lhs), strlen($rhs));
    }
}