<?php
/**
 * Updates the vendor/autoload.php so it manually includes any files specified in composer.json's files array.
 * See https://github.com/composer/composer/issues/6768
 */
$composer = json_decode(file_get_contents(__DIR__ . '/../../../../composer.json'));

const STRING_AUTOLOAD_REAL = "require_once __DIR__ . '/composer/autoload_real.php';";

$files = $composer->autoload->priority ?? [];

if (! $files || ! is_array($files)) {
    print "No files specified -- nothing to do." . PHP_EOL;
    exit;
}

$patch_string = '';
foreach ($files as $f) {
    $patch_string .= "require_once __DIR__ . '/../{$f}';" . PHP_EOL;
}

// Read and re-write the vendor/autoload.php
$autoload = file_get_contents(__DIR__ . '/../../../autoload.php');
$autoload = str_replace(
    STRING_AUTOLOAD_REAL,
    $patch_string . STRING_AUTOLOAD_REAL,
    $autoload);

file_put_contents(__DIR__ . '/../../../autoload.php', $autoload);
