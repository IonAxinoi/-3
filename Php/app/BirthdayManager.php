<?php

class BirthdayManager {
    private $filename;

    public function __construct($filename) {
        $this->filename = $filename;
    }

    public function addEntry($name, $date) {
        if (!$this->validateDate($date)) {
            echo "Некорректная дата: $date\n";
            return false;
        }
        
        file_put_contents($this->filename, "$name, $date\n", FILE_APPEND);
        echo "Данные успешно сохранены.\n";
        return true;
    }

    public function getFilename() {
        return $this->filename;
    }

    private function validateDate($date, $format = 'd-m-Y') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
