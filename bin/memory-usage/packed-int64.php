<?php

\ini_set('memory_limit', -1);

$before = \memory_get_usage();

require __DIR__ . '/../../src/TypedArrayInterface.php';
require __DIR__ . '/../../src/IntArrayInterface.php';
require __DIR__ . '/../../src/TypedArray.php';
require __DIR__ . '/../../src/Int64Array.php';

$result = new \Serafim\PackedArray\Int64Array(
    \str_repeat("\0", (int)$argv[1])
);

echo \memory_get_usage() - $before;

// Save the refcount > 0: Disable the GC
isset($result);
