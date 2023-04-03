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

### Read/Write

Please note that such arrays are designed to store a large amount of data,
however, they are noticeably slower than those builtin PHP arrays during reading
and writing.

#### Reading

| subject            | revs  | its | mem_peak | mode    | rstdev  |
|--------------------|-------|-----|----------|---------|---------|
| benchNative        | 10000 | 5   | 1.291mb  | 0.018μs | ±1.83%  |
| benchSplFixedArray | 10000 | 5   | 1.291mb  | 0.028μs | ±3.06%  |
| benchPackedInt8    | 10000 | 5   | 1.291mb  | 0.087μs | ±1.37%  |
| benchPackedUInt8   | 10000 | 5   | 1.291mb  | 0.079μs | ±1.65%  |
| benchPackedInt16   | 10000 | 5   | 1.291mb  | 0.104μs | ±1.12%  |
| benchPackedUInt16  | 10000 | 5   | 1.291mb  | 0.108μs | ±10.24% |
| benchPackedInt32   | 10000 | 5   | 1.291mb  | 0.159μs | ±1.06%  |
| benchPackedUInt32  | 10000 | 5   | 1.291mb  | 0.174μs | ±0.98%  |
| benchPackedInt64   | 10000 | 5   | 1.291mb  | 0.160μs | ±0.55%  |
| benchPackedFloat32 | 10000 | 5   | 1.291mb  | 1.225μs | ±1.46%  |
| benchPackedFloat64 | 10000 | 5   | 1.291mb  | 1.232μs | ±1.33%  |


#### Writing

| subject            | revs  | its | mem_peak | mode    | rstdev |
|--------------------|-------|-----|----------|---------|--------|
| benchNative        | 10000 | 5   | 1.291mb  | 0.019μs | ±2.52% |
| benchSplFixedArray | 10000 | 5   | 1.291mb  | 0.032μs | ±2.10% |
| benchPackedInt8    | 10000 | 5   | 1.291mb  | 0.088μs | ±2.45% |
| benchPackedUInt8   | 10000 | 5   | 1.291mb  | 0.089μs | ±0.50% |
| benchPackedInt16   | 10000 | 5   | 1.291mb  | 0.108μs | ±1.42% |
| benchPackedUInt16  | 10000 | 5   | 1.291mb  | 0.121μs | ±4.27% |
| benchPackedInt32   | 10000 | 5   | 1.291mb  | 0.146μs | ±1.49% |
| benchPackedUInt32  | 10000 | 5   | 1.291mb  | 0.153μs | ±1.62% |
| benchPackedInt64   | 10000 | 5   | 1.291mb  | 0.220μs | ±1.79% |
| benchPackedFloat32 | 10000 | 5   | 1.291mb  | 1.268μs | ±0.91% |
| benchPackedFloat64 | 10000 | 5   | 1.291mb  | 1.343μs | ±0.44% |

## Installation

This library is available as Composer repository and can be 
installed using the following command in a root of your project:

```bash
$ composer require serafim/packed-array
```

## Quick Start

TODO
