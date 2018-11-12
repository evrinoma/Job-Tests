<?php /* $Id */
if (!defined('SITE_IS_AUTH')) {
    die('No direct script access allowed');
}

define("IS_TIME_TEST", true);

function calcTime($timeStart, $timeEnd, $isClass = true)
{
    echo '<b>Total Execution '.(($isClass) ? 'in class' : 'in funct').' Time:</b> '.sprintf("%f", ($timeEnd - $timeStart)).'<br>';
}

$aTest1 = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];

$aTest2 =     [
        'parent.child.field'      => 1,
        'parent.child.field2'     => 2,
        'parent2.child.name'      => 'test',
        'parent2.child2.name'     => 'test',
        'parent2.child2.position' => 10,
        'parent3.child3.position' => 10,
    ];
print_r($aTest1);
$timeStart = microtime(true);
$test1     = new Test1($aTest1);
$timeEnd   = microtime(true);
(!defined('IS_TIME_TEST')) ? $test1->print_r() : calcTime($timeStart, $timeEnd);
echo "<br>"; 
$timeStart = microtime(true);
$result    = [];
array_filter(
    $aTest1,
    function ($value) use (&$result) {
        $result = (!$result) ? array($value => null) : array($value => $result);
    }
);
$timeEnd = microtime(true);
(!defined('IS_TIME_TEST')) ? print_r($result) : calcTime($timeStart, $timeEnd, false);
echo "<br>";

print_r($aTest2);
$timeStart = microtime(true);
$test2     = new Test2($aTest2);
$timeEnd   = microtime(true);
(!defined('IS_TIME_TEST')) ? $test2->printR() : calcTime($timeStart, $timeEnd);
echo "<br>";
$timeStart = microtime(true);
$result    = [];
$data1     = $aTest2;
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
$timeEnd = microtime(true);
(!defined('IS_TIME_TEST')) ? print_r($result) : calcTime($timeStart, $timeEnd, false);
echo "<br>";

$timeStart = microtime(true);
$test2->getReverse();
$timeEnd = microtime(true);
(!defined('IS_TIME_TEST')) ? $test2->printR() : calcTime($timeStart, $timeEnd);

$timeStart   = microtime(true);
$keyString   = "";
$converted   = array();
$callReverse = function ($data, $key) use (&$callReverse, &$keyString, &$converted) {
    if (is_array($data)) {
        $saveKeyString = $keyString;
        $keyString     .= $key.".";
        array_walk($data, $callReverse);
        $keyString = $saveKeyString;
    } else {
        $converted[$keyString.$key] = $data;
    }
};
array_walk($result, $callReverse);
$timeEnd = microtime(true);
(!defined('IS_TIME_TEST')) ? print_r($converted) : calcTime($timeStart, $timeEnd, false);
echo "<br>";