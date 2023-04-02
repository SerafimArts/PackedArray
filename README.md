<p align="center">
    <a href="https://packagist.org/packages/serafim/packed-array"><img src="https://poser.pugx.org/serafim/packed-array/require/php?style=for-the-badge" alt="PHP 8.1+"></a>
    <a href="https://packagist.org/packages/serafim/packed-array"><img src="https://poser.pugx.org/serafim/packed-array/version?style=for-the-badge" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/serafim/packed-array"><img src="https://poser.pugx.org/serafim/packed-array/v/unstable?style=for-the-badge" alt="Latest Unstable Version"></a>
    <a href="https://packagist.org/packages/serafim/packed-array"><img src="https://poser.pugx.org/serafim/packed-array/downloads?style=for-the-badge" alt="Total Downloads"></a>
    <a href="https://raw.githubusercontent.com/SerafimArts/PackedArray/master/LICENSE.md"><img src="https://poser.pugx.org/serafim/packed-array/license?style=for-the-badge" alt="License MIT"></a>
</p>
<p align="center">
    <a href="https://github.com/SerafimArts/PackedArray/actions"><img src="https://github.com/SerafimArts/PackedArray/workflows/tests/badge.svg"></a>
</p>

## Introduction

PHP packed (typed) arrays are array-like objects that provide a mechanism for 
reading and writing raw binary data in memory buffers with reduced memory 
consumption on large amounts of data.

Common PHP arrays grow and shrink dynamically and can have any value. PHP Zend 
VM perform optimizations so that these arrays are fast. However, in some cases, 
the standard functionality is not enough and the standard PHP array can take up 
a very large amount of data, for example, when working with audio, image and 
video. This is where typed arrays come in. Each entry in a PHP typed array is a 
raw binary value in one of a number of supported formats, from 8-bit integers to
64-bit floating-point numbers.

For example, an image with a size of **44.968Kb**, when loaded into a typed
array, takes **45.168Kb** of memory. However, if it is unpacked into a native
PHP array, then the size of such an image in memory will take **5,633.360Kb**.
That is more than 120 times!

Below is a graph of the size of the consumed RAM depending on the size of the 
array (number of elements).

![/resources/memory-usage.png](/resources/memory-usage.png)

> See the [bin/memory-usage.php](bin/memory-usage.php) for details on how the
> this RAM consumption was calculated.

Please note that such arrays are designed to store a large amount of data,
however, they are noticeably slower than those builtin PHP arrays during reading
and writing.

### Reading

```
+---------------------+--------+-----+----------+---------+--------+
| subject             | revs   | its | mem_peak | mode    | rstdev |
+---------------------+--------+-----+----------+---------+--------+
| benchNative         | 100000 | 10  | 1.291mb  | 0.014μs | ±1.50% |
| benchSplFixedArray  | 100000 | 10  | 1.291mb  | 0.023μs | ±0.75% |
| benchPackedInt8     | 100000 | 10  | 1.291mb  | 0.076μs | ±1.79% |
| benchPackedUInt8    | 100000 | 10  | 1.291mb  | 0.071μs | ±0.57% |
| benchPackedInt16    | 100000 | 10  | 1.291mb  | 0.088μs | ±2.07% |
| benchPackedUInt16le | 100000 | 10  | 1.291mb  | 0.096μs | ±2.83% |
| benchPackedUInt16be | 100000 | 10  | 1.291mb  | 0.097μs | ±1.65% |
| benchPackedInt32    | 100000 | 10  | 1.291mb  | 0.150μs | ±2.18% |
| benchPackedUInt32le | 100000 | 10  | 1.291mb  | 0.166μs | ±1.73% |
| benchPackedUInt32be | 100000 | 10  | 1.291mb  | 0.164μs | ±0.51% |
+---------------------+--------+-----+----------+---------+--------+
```

### Writing

```
+---------------------+--------+-----+----------+---------+--------+
| subject             | revs   | its | mem_peak | mode    | rstdev |
+---------------------+--------+-----+----------+---------+--------+
| benchNative         | 100000 | 10  | 1.291mb  | 0.014μs | ±3.53% |
| benchSplFixedArray  | 100000 | 10  | 1.291mb  | 0.027μs | ±1.37% |
| benchPackedInt8     | 100000 | 10  | 1.291mb  | 0.081μs | ±0.23% |
| benchPackedUInt8    | 100000 | 10  | 1.291mb  | 0.080μs | ±0.76% |
| benchPackedInt16    | 100000 | 10  | 1.291mb  | 0.097μs | ±0.45% |
| benchPackedUInt16le | 100000 | 10  | 1.291mb  | 0.109μs | ±1.20% |
| benchPackedUInt16be | 100000 | 10  | 1.291mb  | 0.107μs | ±1.21% |
| benchPackedInt32    | 100000 | 10  | 1.291mb  | 0.029μs | ±3.62% |
| benchPackedUInt32le | 100000 | 10  | 1.291mb  | 0.027μs | ±2.47% |
| benchPackedUInt32be | 100000 | 10  | 1.291mb  | 0.027μs | ±0.90% |
+---------------------+--------+-----+----------+---------+--------+
```

## Installation

This library is available as Composer repository and can be 
installed using the following command in a root of your project:

```bash
$ composer require serafim/packed-array
```

## Quick Start

TODO
