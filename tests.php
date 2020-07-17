<?php

require "tasks.php";

function ensure(callable $c, array $args, array $results): array
{
    foreach ($args as $key => $singleArgs) {
        $result = call_user_func($c, $singleArgs);
        if ($results[$key] !== $result) {
            // Float numbers are not accurate
            if (is_float($results[$key]) && is_float($result)) {
                if (abs($results[$key] - $result) < 1e-10) {
                    continue;
                }
            }
            return [
                'success'  => false,
                'index'    => $key,
                'arg'      => convertToStringIfIsArray($singleArgs),
                'expected' => convertToStringIfIsArray($results[$key]),
                'returned' => convertToStringIfIsArray($result)
            ];
        }
    }
    return ['success' => true];
}

function convertToStringIfIsArray($value) {
    if (!is_array($value)) {
        return $value;
    }
    $value = print_r($value, true);
    return "<pre>$value</pre>";
}

$tests = [
    [
        'task1',
        ['Макс', 'Света', 'Вова', 'Катя'],
        ['Максим', 'Светлана', 'Владимир', 'Екатерина'],
    ], [
        'task2',
        [1, 2, 4, 8, 11, 22],
        ['1 человек', '2 человека', '4 человека', '8 человек', '11 человек', '22 человека'],
    ], [
        'task3',
        [[1,2,3,4], [2,3,4,5,6], [], [2]],
        [4, 5, 0, 1],
    ], [
        'task4',
        [1, 4, 5, 7, 8, 9],
        ['один', 'четыре', 'пять', 'семь', 'восемь', 'девять'],
    ], [
        'task5',
        [[1,2,3,4], [2,3,4,5,6], [], [2]],
        [[1,2,3,4,177], [2,3,4,5,6,177], [177], [2,177]],
    ], [
        'task6',
        [[450,-150,275], [245,360,567,789,0], ['корова', 'гусь']],
        [[-150,275], [360,567,789,0], ['гусь']],
    ], [
        'task7',
        [[450,-150,275], [245,360,567,789,0], ['корова', 'гусь']],
        [[450,-150], [245,360,567,789], ['корова']],
    ],
];

$testsResult = [];

foreach ($tests as $test) {
    $testsResult[] = ensure($test[0], $test[1], $test[2]);
}
