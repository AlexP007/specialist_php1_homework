<?php

require 'tests.php';

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Проверка домашней работы</title>
    <style>
        body {
            background: lightgray;
        }
        li {
            padding: 5px 10px;
            line-height: 125%;
        }
    </style>
</head>
<body>
<h2>Проверка домашней работы</h2>
<ul>
<?php foreach ($testsResult as $key => $result): ?>
    <li>
        <?php if($result['success']): ?>
            <span style="color: lightgreen">Задание <?=$key+1?> принято</span>
        <?php else: ?>
            <span style="color: crimson">Задание <?=$key+1?> не принято</span><br>
            <span style="color: crimson">Тест <?=$result['index']+1?> не пройден</span><br>
            <?php if($result['arg']): ?>
                Исходное значение: <?=$result['arg']?><br>
            <?php endif;?>
            Ожидаемое значение: <?= $result['expected']?><br>
        <?php endif; ?>
    </li>
<?php endforeach;?>
</ul>
</body>
</html>

