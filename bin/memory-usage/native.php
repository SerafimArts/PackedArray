<?php

\ini_set('memory_limit', -1);

$before = \memory_get_usage();

$result = \range(0, (int)$argv[1]);

echo \memory_get_usage() - $before;

// Save the refcount > 0: Disable the GC
isset($result);
