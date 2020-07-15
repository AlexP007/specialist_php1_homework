<?php


require "tasks.php";

function ensure(callable $c, array $args, array $results): array
{
    foreach ($args as $key => $singleArgs) {
        $result = call_user_func($c, $singleArgs);
        if ($results[$key] !== $result) {
            // Float numbers are not accurate
            if (is_float($results[$key]) && is_float($result)) {
                if (abs($results[$key] - $result) < PHP_FLOAT_EPSILON) {
                    continue;
                }
            }
            return [
                'success'  => false,
                'index'    => $key,
                'arg'      => $singleArgs,
                'expected' => $results[$key],
                'returned' => $result
            ];
        }
    }
    return ['success' => true];
}

$tests = [
    [
        'task1',
        [1000, 25660, 56700],
        [1, 25.66, 56.7],
    ], [
        'task2',
        [1, 25.66, 56.7],
        [1000, (float) 25660, (float) 56700],
    ], [
        'task3',
        [0, 56, 95],
        [32, 132.8, 203],
    ], [
        'task4',
        [0, 150, 275],
        [-273.15, -123.15, 1.85],
    ], [
        'task5',
        ['450', '-150', '275'],
        [450, -150, 275],
    ], [
        'task6',
        [450, -150, 275],
        ['450', '-150', '275'],
    ], [
        'task7',
        [null],
        [date("H:i")],
    ],
];

$testsResult = [];

foreach ($tests as $test) {
    $testsResult[] = ensure($test[0], $test[1], $test[2]);
}
