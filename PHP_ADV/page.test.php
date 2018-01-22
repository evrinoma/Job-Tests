<?php /* $Id */
if (!defined('SITE_IS_AUTH')) {
    die('No direct script access allowed');
}
/* 
 * Copyright (C) 2012 Nikolay Nikolaev (3ABXO3)
 * Email: evrinoma@gmail.com
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of version 2 the GNU General Public
 * License as published by the Free Software Foundation.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 */

//$test1 = new Test1(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h']);
//$test1->print_r();

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