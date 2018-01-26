<?php /* $Id */
if (!defined('SITE_IS_AUTH')) {
    die('No direct script access allowed');
}

$test1 = new Test1(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h']);
$test1->print_r();

$test2 = new Test2(
    [
        'parent.child.field'      => 1,
        'parent.child.field2'     => 2,
        'parent2.child.name'      => 'test',
        'parent2.child2.name'     => 'test',
        'parent2.child2.position' => 10,
        'parent3.child3.position' => 10,
    ]
);

$test2->printR();
$test2->getReverse()->printR();

// @TODO we should compare times execution time
//task1
$result = [];
array_filter(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'],
    function ($value) use (&$result) {
        $result = (!$result) ? array($value => null) : array($value => $result);
    }
);
print_r($result);

//task2
$result = [];
$data1 = [
    'parent.child.field'      => 1,
    'parent.child.field2'     => 2,
    'parent2.child.name'      => 'test',
    'parent2.child2.name'     => 'test',
    'parent2.child2.position' => 10,
    'parent3.child3.position' => 10,
];
array_walk(
    $data1,
    function ($valueData, $key) use (&$result) {
        $item = [];
        array_filter(
            array_reverse(explode('.', $key)),
            function ($value) use (&$item, $valueData) {
                $item = (!$item) ? array($value => $valueData) : array($value => $item);
            }
        );
        $result = array_merge_recursive($result, $item);
    }
);
print_r($result);
//reverse
$keyString = "";
$converted = array();
$callReverse = function($data, $key) use (&$callReverse, &$keyString, &$converted)
{
        if (is_array($data)) {
            $saveKeyString = $keyString;
            $keyString .= $key.".";
            array_walk($data, $callReverse);
            $keyString = $saveKeyString;
        } else {
            $converted[$keyString.$key] = $data;
        }
};
array_walk($result, $callReverse);
print_r($converted);
