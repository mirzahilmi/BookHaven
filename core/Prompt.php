<?php
namespace Saphpi\Core;

use Saphpi\Core\Console\ArgInput;

class Prompt {
    private ArgInput $argInput;

    public function __construct() {
        $this->argInput = new ArgInput();
    }

    public function resolve(): void {
        $args = $this->argInput->getArgs();
        if (empty($args)) {
            return;
        }
        $flags = $this->argInput->getFlags();

        $className = ucwords(array_shift($args));
        $class = "\\Saphpi\\Core\\Console\\Commands\\{$className}";

        try {
            /** @var \Saphpi\Core\Console\Command */
            $command = new $class($args, $flags);
        } catch (\Throwable) {
            echo "{$className} command does not exists" . PHP_EOL;
        }

        $command->handle();
    }
}

$env = parse_ini_file(ROOT . '/.env');
if ($env === false) {
    die('Failed to load .env file');
}

new MySQL(
    $env['DB_HOST'],
    $env['DB_PORT'],
    $env['DB_USERNAME'],
    $env['DB_PASSWORD'],
    $env['DB_SCHEMA']
);
