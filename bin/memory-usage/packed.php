<?php

$before = \memory_get_usage();

require __DIR__ . '/../../src/TypedArrayInterface.php';
require __DIR__ . '/../../src/TypedArray.php';
require __DIR__ . '/../../src/Int8Array.php';

$result = new \Serafim\PackedArray\Int8Array(
    \str_repeat("\0", (int)$argv[1])
);

echo \memory_get_usage() - $before;

// Save the refcount > 0: Disable the GC
isset($result);