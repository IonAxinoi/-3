<?php

function deleteEntry($filename, $search) {
    $lines = file($filename, FILE_IGNORE_NEW_LINES);
    $found = false;
    
    foreach ($lines as $key => $line) {
        if (strpos($line, $search) !== false) {
            unset($lines[$key]);
            $found = true;
        }
    }

    if ($found) {
        file_put_contents($filename, implode(PHP_EOL, $lines));
        echo "Запись удалена.\n";
    } else {
        echo "Запись не найдена.\n";
    }
}
