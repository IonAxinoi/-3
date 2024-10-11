<?php

require_once 'BirthdayManager.php';
require_once 'BirthdayFinder.php';
require_once 'BirthdayDeleter.php';

$filename = 'birthdays.txt';
$birthdayManager = new BirthdayManager($filename);
$birthdayMessage = ''; // Переменная для хранения сообщения

// Проверяем, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_entry'])) {
        // Обработка добавления записи
        $name = $_POST['name'];
        $date = $_POST['date'];
        $birthdayManager->addEntry($name, $date);
    }

    if (isset($_POST['find_birthday'])) {
        // Обработка поиска дней рождений
        $birthdayMessage = findBirthdaysToday($birthdayManager->getFilename());
    }

    if (isset($_POST['delete_entry'])) {
        // Обработка удаления записи
        $searchInput = $_POST['search'];
        deleteEntry($birthdayManager->getFilename(), $searchInput);
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление днями рождения</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
            position: relative; /* Позволяет конфетти позиционироваться */
            overflow: hidden; /* Прячем переполнение от конфетти */
        }

        .birthday-message {
            color: red;
            font-size: 2em;
            display: none; /* Скрыть по умолчанию */
            position: absolute;
            top: 60%; /* Перемещаем ниже */
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10; /* Слой выше конфетти */
        }

        /* Стили для конфетти */
        .confetti {
            position: absolute;
            pointer-events: none;
            z-index: 9999;
        }

        .confetti-piece {
            width: 10px;
            height: 10px;
            position: absolute;
            background-color: #ff0; /* Цвет конфетти по умолчанию */
            opacity: 0.8;
            animation: fall linear infinite;
        }

        @keyframes fall {
            0% { transform: translateY(-100vh); }
            100% { transform: translateY(100vh); }
        }
    </style>
</head>
<body>
    <h1>Управление днями рождения</h1>

    <!-- Форма для добавления записи -->
    <form method="post">
        <h2>Добавить запись</h2>
        <label for="name">Имя:</label>
        <input type="text" name="name" id="name" required>
        <label for="date">Дата рождения (дд-мм-гггг):</label>
        <input type="text" name="date" id="date" required>
        <button type="submit" name="add_entry">Добавить</button>
    </form>

    <!-- Форма для поиска поздравлений -->
    <form method="post">
        <h2>Найти поздравления</h2>
        <button type="submit" name="find_birthday">Найти сегодня</button>
    </form>

    <!-- Форма для удаления записи -->
    <form method="post">
        <h2>Удалить запись</h2>
        <label for="search">Имя или дата:</label>
        <input type="text" name="search" id="search" required>
        <button type="submit" name="delete_entry">Удалить</button>
    </form>

    <!-- Сообщение о дне рождения -->
    <?php if ($birthdayMessage): ?>
        <div class="birthday-message" id="birthdayMessage">
            С днем рождения! <?php echo $birthdayMessage; ?>
        </div>
    <?php endif; ?>

    <script>
        // Функция для генерации случайного цвета
        function randomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        // Функция для создания конфетти
        function createConfetti() {
            const confettiContainer = document.createElement('div');
            confettiContainer.classList.add('confetti');
            document.body.appendChild(confettiContainer);
            for (let i = 0; i < 100; i++) {
                const confettiPiece = document.createElement('div');
                confettiPiece.classList.add('confetti-piece');
                confettiPiece.style.left = Math.random() * 100 + 'vw'; // Случайная позиция по ширине
                confettiPiece.style.animationDuration = Math.random() * 2 + 2 + 's'; // Случайная продолжительность
                confettiPiece.style.backgroundColor = randomColor(); // Случайный цвет
                confettiContainer.appendChild(confettiPiece);
            }
            setTimeout(() => confettiContainer.remove(), 4000); // Удаляем конфетти через 4 секунды
        }

        // Показываем сообщение о дне рождения и конфетти
        window.onload = function() {
            const messageDiv = document.getElementById('birthdayMessage');
            if (messageDiv) {
                messageDiv.style.display = 'block'; // Показываем сообщение
                createConfetti(); // Запускаем конфетти

                // Скрываем сообщение через 5 секунд
                setTimeout(() => {
                    messageDiv.style.display = 'none'; // Скрываем сообщение
                }, 5000);
            }
        };
    </script>
</body>
</html>
