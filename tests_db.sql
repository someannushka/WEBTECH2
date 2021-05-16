-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 15 2021 г., 17:46
-- Версия сервера: 10.1.38-MariaDB
-- Версия PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `tests_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `answer` text,
  `upload` text,
  `created` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `answers`
--

INSERT INTO `answers` (`id`, `student_id`, `question_id`, `answer`, `upload`, `created`) VALUES
(5, 9, 5, '{\"right\":[\"B. Option 2\",\"C. Option 3\"]}', '', 1621085400),
(6, 9, 4, 'test', '', 1621085425),
(7, 9, 8, '$$ \\sqrt(4)\\ $$', '', 1621085695),
(8, 10, 5, '{\"right\":[\"B. Option 2\",\"D. Option 4\"]}', '', 1621086691),
(9, 10, 4, 'ets', '', 1621086699),
(14, 10, 7, '', '[\"C:\\/\\/WEB\\/localhost\\/tests_project\\/images\\/10_7_1.PNG\",\"C:\\/\\/WEB\\/localhost\\/tests_project\\/images\\/10_7_local.jpg\"]', 1621088766),
(15, 11, 5, '{\"right\":[\"B. Option 2\"]}', '', 1621090351),
(16, 11, 4, 'answer', '', 1621090360),
(17, 11, 8, '$$ \\sqrt(4)\\ $$', 'null', 1621090383),
(18, 11, 7, '', '[\"C:\\/\\/WEB\\/localhost\\/tests_project\\/images\\/11_7_1.PNG\"]', 1621090394),
(19, 11, 6, '{\"right\":[{\"position\":0,\"option\":\" one\"},{\"position\":1,\"option\":\" two\"},{\"position\":2,\"option\":\" three\"},{\"position\":3,\"option\":\" four\"}]}', '', 1621091304),
(20, 13, 5, '{\"right\":[\"B. Option 2\"]}', '', 1621093215),
(21, 13, 4, 'test', '', 1621093227),
(22, 13, 6, '{\"right\":[{\"position\":0,\"option\":\" one\"},{\"position\":1,\"option\":\" two\"},{\"position\":2,\"option\":\" three\"},{\"position\":3,\"option\":\" four\"}]}', '', 1621093231),
(23, 13, 8, '$$ \\sqrt(4)\\ = 2 $$', '', 1621093249);

-- --------------------------------------------------------

--
-- Структура таблицы `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `test_id` int(11) DEFAULT NULL,
  `sorted` int(11) DEFAULT NULL,
  `type` varchar(128) DEFAULT NULL,
  `description` text,
  `meta` text,
  `created` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `questions`
--

INSERT INTO `questions` (`id`, `test_id`, `sorted`, `type`, `description`, `meta`, `created`) VALUES
(4, 11, 3, '1', 'If $a \\ne 0$, then $ax^2 + bx + c = 0$ has two solutions,\r\n  $$x = {-b \\pm \\sqrt{b^2-4ac} \\over 2a}.$$', '{\"open_answer\":\"first | second | third\",\"checkbox_answer\":\"\",\"pairs_answer\":\"\",\"json_open_answer\":\"{\\\"right\\\":[\\\"first\\\",\\\"second\\\",\\\"third\\\"]}\",\"json_checkbox_answer\":\"\",\"json_pairs_answer\":\"\"}', 1621077123),
(5, 11, 1, '2', 'Select 2:', '{\"open_answer\":\"\",\"checkbox_answer\":\"+ A. Option 1 |\\n- B. Option 2 |\\n- C. Option 3 |\\n- D. Option 4\",\"pairs_answer\":\"\",\"json_open_answer\":\"\",\"json_checkbox_answer\":\"{\\\"right\\\":[{\\\"option\\\":\\\"A. Option 1\\\",\\\"status\\\":true},{\\\"option\\\":\\\"B. Option 2\\\",\\\"status\\\":false},{\\\"option\\\":\\\"C. Option 3\\\",\\\"status\\\":false},{\\\"option\\\":\\\"D. Option 4\\\",\\\"status\\\":false}]}\",\"json_pairs_answer\":\"\"}', 1621077724),
(6, 11, 4, '3', 'Fit pairs:', '{\"open_answer\":\"\",\"checkbox_answer\":\"\",\"pairs_answer\":\"\\\"1\\\":: \\\"one\\\" |\\n\\\"2\\\":: \\\"two\\\" |\\n\\\"3\\\":: \\\"three\\\" |\\n\\\"4\\\":: \\\"four\\\" |\",\"json_open_answer\":\"\",\"json_checkbox_answer\":\"\",\"json_pairs_answer\":\"{\\\"right\\\":[{\\\"statement\\\":\\\"\\\\\\\"1\\\\\\\"\\\",\\\"option\\\":\\\"\\\\\\\"one\\\\\\\"\\\"},{\\\"statement\\\":\\\"\\\\\\\"2\\\\\\\"\\\",\\\"option\\\":\\\"\\\\\\\"two\\\\\\\"\\\"},{\\\"statement\\\":\\\"\\\\\\\"3\\\\\\\"\\\",\\\"option\\\":\\\"\\\\\\\"three\\\\\\\"\\\"},{\\\"statement\\\":\\\"\\\\\\\"4\\\\\\\"\\\",\\\"option\\\":\\\"\\\\\\\"four\\\\\\\"\\\"}]}\"}', 1621077235),
(7, 11, 1, '4', 'Draw:', '{\"open_answer\":\"\",\"checkbox_answer\":\"\",\"pairs_answer\":\"\"}', 1621053623),
(8, 11, 5, '5', 'Math:', '{\"open_answer\":\"\",\"checkbox_answer\":\"\",\"pairs_answer\":\"\"}', 1621053648);

-- --------------------------------------------------------

--
-- Структура таблицы `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `test_id` int(11) DEFAULT NULL,
  `first_name` varchar(128) DEFAULT NULL,
  `last_name` varchar(128) DEFAULT NULL,
  `created` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `students`
--

INSERT INTO `students` (`id`, `test_id`, `first_name`, `last_name`, `created`) VALUES
(1, 11, 'Stud', 'Stud', 1621052816),
(2, 11, 'Stud 2', 'Stud 2', 1621052846),
(3, 11, 'Stud 3', 'Stud', 1621052928),
(4, 11, 'student', '1', 1621053716),
(5, 11, 'Stud', 'stud', 1621070984),
(6, 11, 'stud', 'stud', 1621071330),
(7, 11, 'stud', 'test', 1621078782),
(8, 11, 'stud', 'stud', 1621081988),
(9, 11, 'stud', 'stud', 1621085167),
(10, 11, 'stud', 'stud', 1621086686),
(11, 11, 'test', 'test', 1621090348),
(12, 11, 'stud', 'stud', 1621093177),
(13, 11, 'stud', 'stud', 1621093208);

-- --------------------------------------------------------

--
-- Структура таблицы `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `login` varchar(128) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `created` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `teachers`
--

INSERT INTO `teachers` (`id`, `login`, `password`, `created`) VALUES
(3, 'teacher_admin', 'c25fa05012cd38f6a663069ad9276a35', 1620985350);

-- --------------------------------------------------------

--
-- Структура таблицы `tests`
--

CREATE TABLE `tests` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `hash` varchar(128) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `time_limit` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `tests`
--

INSERT INTO `tests` (`id`, `teacher_id`, `name`, `hash`, `status`, `time_limit`, `created`) VALUES
(11, 3, 'Initial', '5bIohLIclQ', 1, 5, 1621093155);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
