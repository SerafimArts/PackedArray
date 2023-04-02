<?php

const SAMPLES = 30;
const EXEC_FROM = __DIR__ . '/memory-usage';
const EXEC_INTO = __DIR__ . '/../resources';

for ($i = 0, $size = 1, $result = []; $i < SAMPLES; ++$i) {
    $result[] = [
        'size' => $size,
        'native' => ((int)exec('php ' . EXEC_FROM . "/native.php $size") / 1000),
        'packed' => ((int)exec('php ' . EXEC_FROM . "/packed.php $size") / 1000),
    ];

    $size *= 2;
}

/**
 * The original png was generated using:
 * @link https://www.encodedna.com/google-chart/make-charts-using-json-data-dynamically.htm
 */
file_put_contents(EXEC_INTO . '/memory-usage.json', json_encode($result));


