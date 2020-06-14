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
     * @var array карта-хранилище
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
     * Найти наиболее подходящий ключ для строки
     * @param string $input исходная строка
     * @param float $minSimilarityIndex минимальный индекс соответствия
     * @return mixed
     * @throws NotFoundException
     */
    public function find(string $input, float $minSimilarityIndex = -1)
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

        if($bestId === null || $bestSimilarityIndex < $minSimilarityIndex) {
            throw new NotFoundException();
        }

        return $bestId;
    }

    /**
     * Добавить сопоставление ключа набору строк
     * @param mixed $index ключ
     * @param array $strings набор строк
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
     * Получить индекс похожести двух строк
     * @param string $lhs первая строка
     * @param string $rhs вторая строка
     * @return float индекс похожести
     */
    protected function compareStrings(string $lhs, string $rhs): float
    {
        return similar_text($lhs, $rhs)/min(strlen($lhs), strlen($rhs));
    }
}