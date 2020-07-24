<?php
declare(strict_types=1);

removeFinal('vendor/nette/php-generator/src/PhpGenerator/ClassType.php');
removeFinal('vendor/nette/php-generator/src/PhpGenerator/Property.php');
removeFinal('vendor/nette/php-generator/src/PhpGenerator/Method.php');
removeFinal('vendor/nette/php-generator/src/PhpGenerator/PhpNamespace.php');

function removeFinal(string $fileName)
{
    $projectDir = dirname(__DIR__) . '/';

    $filePath = $projectDir . $fileName;
    $fileContent = file_get_contents($filePath);
    $fileWithoutFinal = str_replace(['final class', 'final function'], ['class', 'function'], $fileContent);

    file_put_contents($filePath, $fileWithoutFinal);
}
