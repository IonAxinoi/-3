<?php

function findBirthdaysToday($filename) {
    $today = date('d-m'); // Получаем текущую дату в формате дд-мм
    $birthdaysToday = [];

    if (file_exists($filename)) {
        $lines = file($filename, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            // Проверяем, соответствует ли строка формату
            if (preg_match('/^(.+), (\d{2}-\d{2}-\d{4})$/', $line, $matches)) {
                $name = trim($matches[1]);
                $date = trim($matches[2]);

                // Сравниваем только день и месяц
                if (date('d-m', strtotime($date)) === $today) {
                    $birthdaysToday[] = $name;
                }
            }
        }
    }

    // Возвращаем имена, если есть совпадения, иначе пустую строку
    return empty($birthdaysToday) ? '' : implode(', ', $birthdaysToday);
}
