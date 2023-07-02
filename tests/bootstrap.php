<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

require dirname(__DIR__) . '/vendor/autoload.php';

if (isset($_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'])) {
  // executes the "php bin/console cache:clear" command
    passthru(sprintf(
        'APP_ENV=%s php "%s/../bin/console" cache:clear --no-warmup',
        $_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'],
        __DIR__
    ));
}

if (file_exists(dirname(__DIR__) . '/config/bootstrap.php')) {
    require dirname(__DIR__) . '/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}

$process = new Process(['php', 'bin/console', 'doctrine:database:drop', '--no-interaction', '--if-exists', '--force']);
$process->run();

if (! $process->isSuccessful()) {
    throw new ProcessFailedException($process);
}

$process = new Process(['php', 'bin/console', 'doctrine:database:create', '--no-interaction']);
$process->run();

if (! $process->isSuccessful()) {
    throw new ProcessFailedException($process);
}

$process = new Process(['php', 'bin/console', 'doctrine:schema:create', '--no-interaction']);
$process->run();

if (! $process->isSuccessful()) {
    throw new ProcessFailedException($process);
}

$process = new Process(['php', 'bin/console', 'doctrine:migrations:migrate', '--no-interaction', '--allow-no-migration']);
$process->run();

if (! $process->isSuccessful()) {
    throw new ProcessFailedException($process);
}

$process = new Process(['php', 'bin/console', 'doctrine:fixtures:load', '--no-interaction']);
$process->run();

if (! $process->isSuccessful()) {
    throw new ProcessFailedException($process);
}
