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
        [4, 23, 13, 2],
        ['1234', '1234567891011121314151617181920212223', '12345678910111213', '2'],
    ], [
        'task2',
        [1, 2, 4, 8, 11, 22],
        [
            '12345678910111213141516171819202122232425',
            '2345678910111213141516171819202122232425',
            '45678910111213141516171819202122232425',
            '8910111213141516171819202122232425',
            '111213141516171819202122232425',
            '232425'
        ],
    ], [
        'task3',
        [[1,4], [2,6], [6,9]],
        ['1 2 3 4', '2 3 4 5 6', '6 7 8 9'],
    ], [
        'task4',
        [[
            'Пупыркин' => 245,
            'Адропов'  => 459,
            'Лимонов'  => 2045,
            'Каролек'  => 2471,
        ],[
            'Пупыркин' => 1045,
            'Адропов'  => 4559,
            'Персиков' => 45,
            'Алуша'    => 711,
        ]],
        [[
            'Пупыркин, население: 245 человек',
            'Адропов, население: 459 человек',
            'Лимонов, население: 2045 человек',
            'Каролек, население: 2471 человек',
        ],[
            'Пупыркин, население: 1045 человек',
            'Адропов, население: 4559 человек',
            'Персиков, население: 45 человек',
            'Алуша, население: 711 человек',
        ]],
    ], [
        'task5',
        [[1,2,3,4], [2,3,4,5,6], [177], [2,56,7]],
        [[2,4], [3,5], [], [56]],
    ],
];

$testsResult = [];

foreach ($tests as $test) {
    $testsResult[] = ensure($test[0], $test[1], $test[2]);
}
