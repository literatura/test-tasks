# test-cases

## Тестовое задание 1
Находится в файле index.php

Если передать GET-параметр test, т.е. вызвать index.php?test , то будут созданы и просчитаны тестовые файлы

Если параметр test не указан, то нужно передать параметр filename, т.е. index.php?filename=realfile.some

Важно! файл realfile.some должен лежать на том же уровне, что и файл index.php


Решение в ветке master показывает на тестовом файле следующее время

* Bits: 112 ; Time: 3.3140182495117E-5
* Bits: 2100000 ; Time: 0.077265024185181 

Решение в ветке counting-by-string-actions

* Bits: 112 ; Time: 3.8862228393555E-5
* Bits: 2100000 ; Time: 0.35030293464661 


## Тестовое задание 2
```sql
SELECT e.id, e.name
FROM event e
    JOIN user_event AS ue ON ue.event_id = e.id
    JOIN user AS u ON u.id=ue.user_id
WHERE u.gender="female"
GROUP BY e.id
HAVING AVG(u.age) > 30
```