<?php


namespace Smoren\SimilarStringFinder;


class SimilarStringFinderExample extends SimilarStringFinder
{
    public function __construct()
    {
        parent::__construct();

        $this->addStrings('Окно', ['Окно', 'Окошко']);
        $this->addStrings('Небо', ['Небо', 'Небеса']);
    }

    public static function test()
    {
        $inst = new static();
        echo "оконный => " . $inst->find('оконный') . "\n";
        echo "небесный => " . $inst->find('небесный') . "\n";
    }
}