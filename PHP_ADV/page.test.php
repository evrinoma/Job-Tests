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