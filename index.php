<?php
/**
 * Задание 1: Имеется файл размером 8 Гб. 
 * Требуется подсчитать сколько бит в этом файле имеют значение 1. 
 * Написать решение и оптимизировать его.
 * 
 * Для решения используется способ замены 0 в бинарной строке на '' и подсчет длины этой строки
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

function processFile($filename) {
    $handle = fopen($filename, 'rb');
    $oneBitsCount = 0;

    while (($buffer = fgets($handle, 4096)) !== false) {
        for($l=strlen($buffer), $i=0; $i<$l; $i++) {
            $binary = sprintf('%08b', ord($buffer[$i]));
            $oneBitsCount += calcOneBitsCount($binary);
        }
    }

    fclose($handle);

    return $oneBitsCount;
}

function calcOneBitsCount($string) {
    $string = str_replace('0', '', $string);

    return strlen($string);
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