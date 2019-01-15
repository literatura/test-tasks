<?php
/**
 * Задание 1: Имеется файл размером 8 Гб. 
 * Требуется подсчитать сколько бит в этом файле имеют значение 1. 
 * Написать решение и оптимизировать его.
 * 
 * Для решения используется теоретическая часть, преставленная в статье https://habr.com/post/276957/
 */

ini_set('max_execution_time', 2000);

try {
    $testMode = checkTestMode();

    if (checkTestMode()) {
        $filename = __DIR__ . '/test.bin';

        createTestFile($filename);

        $startTime = microtime(true); 
        $bitsCount = processFile($filename);
        $deltaTime = microtime(true) - $startTime;

        showReport($bitsCount, $deltaTime);

        createTestFile($filename, 300000);

        $startTime = microtime(true); 
        $bitsCount = processFile($filename);
        $deltaTime = microtime(true) - $startTime;

        showReport($bitsCount, $deltaTime);
    } else {
        $filename = getFilename();

        $startTime = microtime(true); 
        $bitsCount = processFile($filename);
        $deltaTime = microtime(true) - $startTime;

        showReport($bitsCount, $deltaTime);
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();

    exit();
}

function processFile($filename) 
{
    if (!$handle = fopen($filename, 'rb')) {
        throw new Exception("Can not open file $filename for read");
    }

    $oneBitsCount = 0;

    while ($chunk = fread($handle, 4)) {
        $str = hexdec(bin2hex($chunk));
        $oneBitsCount += calcOneBitsCount($str);
    }

    fclose($handle);

    return $oneBitsCount;
}

function calcOneBitsCount($value) 
{
    $result = $value - (($value >> 1) & 0x55555555); 
    $result = (($result >> 2) & 0x33333333) + ($result & 0x33333333);
    $result = (($result >> 4) + $result) & 0x0F0F0F0F; 
    $result = (($result >> 8) + $result) & 0x00FF00FF;
    $result = (($result >> 16) + $result) & 0x0000FFFF;

    return $result;
}

function checkTestMode() 
{
    return isset($_GET['test']);
}

function getFilename()
{
    if (!empty($_GET['filename'])) {
        $filename = realpath(__DIR__ . '/' .$_GET['filename']);

        // TODO тут нужно проверять, что файл запрашивается из текущей папки

        if (!$filename || !file_exists($filename) || !is_readable($filename)) {
            throw new Exception('File not found or not readable.');
        }
    } else {
        throw new Exception("File name is empty. Add ?filename=... to URL");
    }

    return $filename;
}

function createTestFile(string $filename, int $count = 16, int $number = 127) 
{
    if (!$file = fopen ($filename, 'wb')) {
        throw new Exception("Can not open file $filename for write");
    }

    for ($i=0; $i < $count; $i++) { 
        fputs($file, pack('s', $number));
    }
    
    fclose($file);
}

function showReport($bitsCount, $time) 
{
    echo "Bits: $bitsCount ; Time:  $time <br />";
}