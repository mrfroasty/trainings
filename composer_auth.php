#!/usr/bin/env php
<?php

$configContent = <<<EOT
{
    "github-oauth": {
        "github.com": "{{GITHUB_OAUTH_TOKEN}}"
    }
}

EOT;

$credentials = [
    'GITHUB_OAUTH_TOKEN' => isset($argv[1]) ? $argv[1] : null
];
foreach ($credentials as $entryKey => $entryValue) {
    $configContent = str_replace("{{" . $entryKey . "}}", $entryValue, $configContent, $count);
}

try {
    $composerHome = !isset($argv[2]) ? "/root/.composer" : $argv[2];
    exec("mkdir -pv {$composerHome}");

    $fp = fopen($composerHome . "/auth.json", 'w');
    fwrite($fp, $configContent);
    fclose($fp);
} catch (Exception $e) {
    echo PHP_EOL . $e->getMessage();
    exit(255);
}
