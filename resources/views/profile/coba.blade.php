<?php

/*
 * Complete the 'arrayManipulation' function below.
 *
 * The function is expected to return a LONG_INTEGER.
 * The function accepts following parameters:
 *  1. INTEGER n
 *  2. 2D_INTEGER_ARRAY queries
 */

function arrayManipulation($n, $queries) {
    // Use a difference array to apply range additions in O(m + n) time
    $diff = array_fill(0, $n + 2, 0);

    foreach ($queries as $q) {
        $a = intval($q[0]);
        $b = intval($q[1]);
        $k = intval($q[2]);

        $diff[$a] += $k;
        $diff[$b + 1] -= $k;
    }

    $max = 0;
    $current = 0;
    for ($i = 1; $i <= $n; $i++) {
        $current += $diff[$i];
        if ($current > $max) {
            $max = $current;
        }
    }

    return $max;
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$first_multiple_input = explode(' ', rtrim(fgets(STDIN)));

$n = intval($first_multiple_input[0]);

$m = intval($first_multiple_input[1]);

$queries = array();

for ($i = 0; $i < $m; $i++) {
    $queries_temp = rtrim(fgets(STDIN));

    $queries[] = array_map('intval', preg_split('/ /', $queries_temp, -1, PREG_SPLIT_NO_EMPTY));
}

$result = arrayManipulation($n, $queries);

fwrite($fptr, $result . "\n");

fclose($fptr);
