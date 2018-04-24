<?php

namespace phmLabs\EsLintBridge;


class EsLinter
{
    public function lint($htmlContent)
    {
        $file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . md5(microtime()) . '.html';
        file_put_contents($file, $htmlContent);

        $command = __DIR__ . '/../eslint/node_modules/.bin/eslint ' . $file . ' -c ' . __DIR__ . '/../eslint/.eslintrc.json --format json';
        exec($command, $plainOutoput, $return);
        unlink($file);

        $output = implode("\n", $plainOutoput);

        if (strpos($output, "Cannot find module") !== false) {
            throw new \RuntimeException('Unable to start eslint. Please run "npm install in directory ' . realpath(__DIR__ . '/../eslint/') . '"');
        }

        $result = json_decode($output, true);

        if ($result[0]['messages']) {
            return $result[0]['messages'];
        } else {
            return [];
        }
    }
}