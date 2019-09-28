<?php

/**
 * Deployment Configuration - madia project template for the latest version of this file
 */

namespace Deployer;
require_once 'recipe/symfony3.php';
require_once 'recipe/cachetool.php';

set('repository', 'git@git.madia.nl:crh/crh-webshop.git');
set('dump_assets', true);
set('default_timeout', 0);
set('http_user', 'nginx');

/**
 * Custom bins - no memory limit
 */
set('bin/php', function () {
    $php = locateBinaryPath('php');
    return $php . ' -dmemory_limit=-1';
});

set('bin/wget', function () {
    return locateBinaryPath('wget');
});

set('bin/unzip', function () {
    return locateBinaryPath('unzip');
});

set('bin/find', function () {
    return locateBinaryPath('find');
});

/**
 * Composer - Dependency handling
 */
set('composer_options', 'install');
task('deploy:vendors', function () {
    run('cd {{release_path}} && {{bin/composer}} {{composer_options}}');
    invoke('deploy:madia:vendor:update');
    invoke('deploy:madia:vendor:remove');
})->desc('Installing vendors');

/**
 * update madia modules
 */
task('deploy:madia:vendor:update', function () {
    $isTaskNotAllowed = ['prod', 'stage', 'test','build'];
    if (input()->hasArgument('stage')) {
        $inputEnv = input()->getArgument('stage');
        if (in_array($inputEnv, $isTaskNotAllowed)) {
            write("<error>deploy:madia:vendor:update is not allowed in prod / stage / test environment!</error>");
            return;
        }
    }
    $modules = [
    ];
    $moduleOption = implode(" ", $modules);
    set("madia_modules_option", $moduleOption);
    run('cd {{release_path}} && {{bin/composer}} update {{madia_modules_option}} -vvv');
})->desc('Updating madia vendors');

/**
 * remove madia modules (only those listed in submodules)
 */
task('deploy:madia:vendor:remove', function () {
    $isTaskNotAllowed = ['prod', 'stage', 'test','build'];
    if (input()->hasArgument('stage')) {
        $inputEnv = input()->getArgument('stage');
        if (in_array($inputEnv, $isTaskNotAllowed)) {
            write("<error>deploy:madia:vendor:update is not allowed in prod / stage / test environment!</error>");
            return;
        }
    }
    $modules = [
        'madia/*'
    ];
    foreach ($modules as $module) {
        run("cd {{release_path}} && rm -rvf vendor/{$module} -vvv");
    }
    invoke('deploy:vendor:dump:autoload');
})->desc('Removing madia vendors');

task('deploy:vendor:dump:autoload', function() {
    run('cd {{release_path}} && {{bin/composer}} dump-autoload');
});

task('deploy:db:backup', function () {
    $isBackupAllowed = ['prod'];
    if (input()->hasArgument('stage')) {
        $inputEnv = input()->getArgument('stage');
        if (in_array($inputEnv, $isBackupAllowed)) {
            run('cd {{release_path}} && mysqldump {{login_path}} --single-transaction | gzip -9 > db-dump_$(date +%Y%m%d-%H%M).sql.gz');
        } else {
            write("<info>Backup allowed only on production - skip!</info>");
        }
    }
})->desc('Keep database backup');


/**
 * Load .env variables as deployer parameters e.g {{FOOBAR}}
 */
task('deploy:dotenv:load', function () {
    require_once getenv('COMPOSER_HOME') . '/vendor/autoload.php';
    $dir = dirname(__FILE__);
    $environment = run("git archive --format=tar --remote={{repository}} {{branch}} .env|(cd {$dir} && tar xf - && cat .env)");
    $dotenv = new \Symfony\Component\Dotenv\Dotenv();
    $dotenv->populate($dotenv->parse($environment));
    array_map(function ($var) {
        set($var, getenv($var));
    }, explode(',', $_SERVER['SYMFONY_DOTENV_VARS']));

    //test
//    invoke('deploy:dotenv:load:test');
});

task('deploy:dotenv:load:test', function () {
    $vars = explode(',', $_SERVER['SYMFONY_DOTENV_VARS']);
    foreach ($vars as $var) {
        run("echo key: '$var' value {{{$var}}}");
    }
});

desc('Run unit tests');
set('phpunit_option', '--color --testsuite="unit"');
task('madia:test:unit', function () {
    if (input()->hasArgument('stage')) {
        $inputEnv = input()->getArgument('stage');
        if (in_array($inputEnv, ['dev','build'])) {
            run("cd {{release_path}} && ./bin/phpunit -c phpunit.xml {{phpunit_option}}");
        }
    }
});

desc('Run PHP Lint');
task('deploy:phplint', function () {
    $dir = [
        'app/code',
    ];
    $paths = implode(" ", $dir);
    run("cd {{release_path}} && {{bin/find}} {$paths} -name \"*.php\" -print0 | xargs -0 -n1 {{bin/php}} -l");
});

foreach (['dev', 'build'] as $env) {
    localhost('DEV#:DOCKER' . $env)
        ->stage($env)
        ->set('deploy_path', function () {
            return getenv('DEPLOY_PATH');
        })
        ->set('current_path', function () {
            return getenv('CURRENT_PATH');
        })
        ->set('release_path', function () {
            return getenv('RELEASE_PATH');
        })
        ->set('http_user', function () {
            return getenv('HTTP_USER');
        })
        ->set('writable_mode', 'chmod')
        ->set('writable_chmod_mode', '777')/* mode prefered */
        ->set('mage_mode', 'developer')/* magento mode */
        ->set('static_content_options', '-f');
}

localhost('TESTING#:staging489.hipex.io -p 339')
    ->stage('test')
    ->set('deploy_path', '~/domains/crh.test-madia.nl')
    ->set('login_path', '--login-path=burghouwt_bap oro_burghouwt_bap')
    ->set('cachetool', '/home/crh/domains/crh.test-madia.nl/var/run/php72.sock')
    ->set('redis-socket', '~/domains/crh.test-madia.nl/var/run/redis.sock')
    ->set('redis-port', '6381');

//localhost('PROD#:staging489.hipex.io -p 339')
//    ->stage('prod')
//    ->set('deploy_path', '~/domains/crh.madia.nl')
//    ->set('login_path', '--login-path=burghouwp_bap bap_burghouwt_prod')
//    ->set('cachetool', '~/domains/crh.madia.nl/var/run/php72.sock')
//    ->set('redis-socket', '~/domains/crh.madia.nl/var/run/redis.sock')
//    ->set('redis-port', '6380');

/**
 * Redis Clear Cache (notice: -n 1 && 4 is session wont be cleared during deployment)
 */
task('deploy:redis:flushall', function () {
    run("redis-cli -p {{redis-port}} flushall");
    run("redis-cli -p {{redis-port}} flushdb");
})->desc('Clear Redis cache');

/**
 * Create index.php - content from app.php
 */
desc('Replacing index.php with app.php');
task('deploy:create:index:file', function () {
    run('cd {{release_path}}/web && cp app.php index.php');
});

/**
 * All assets to the filesystem
 */
task('deploy:oro:assets:install', function () {
    run('{{bin/php}} {{bin/console}} oro:assets:install {{console_options}}');
})->desc('Dump assets - ORO');

/**
 * Migration
 */
task('deploy:oro:migration:load', function () {
    run('{{bin/php}} {{bin/console}} oro:migration:load --force --timeout=0 {{console_options}}');
})->desc('Migration');

task('deploy:oro:migration:data:load', function () {
    run('{{bin/php}} {{bin/console}} oro:migration:data:load {{console_options}}');
})->desc('Migration - Data');

task('deploy:oro:platform:update', function () {
    run('{{bin/php}} {{bin/console}} oro:platform:update --force --timeout=0 {{console_options}}');
})->desc('Migration - Data');

add('shared_files', [
    'config/parameters.yml'
]);

add('shared_dirs', [
    'var/attachment',
    'var/import_export',
    'public/media',
    'public/images',
]);

add('assets', [
    'public/uploads',
    'public/bundles',
    'public/images',
    'public/media',
    'public/js',
    'public/css',
]);

add('writable_dirs', [
    'var/cache',
    'var/logs',
    'var/import_export',
    'var/attachment',
]);

//if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

/**
 * Main tasks - test,stage,prod
 */
task('deploy', function () {
    $mainTasks = [
        'deploy:dotenv:load',
        'deploy:info',
        'deploy:prepare',
        'deploy:lock',
        'deploy:release',
        'deploy:update_code',
        'deploy:clear_paths',
        'deploy:create_cache_dir',
        'deploy:shared',
        'deploy:assets',
        'deploy:vendors',
        'deploy:db:backup',
        'deploy:oro:platform:update',
        'deploy:redis:flushall',
        'deploy:cache:warmup',
        'deploy:writable',
        'deploy:symlink',
        'cachetool:clear:opcache',
        'deploy:unlock',
        'cleanup',
        'success'
    ];

    if (input()->hasArgument('stage')) {
        $inputEnv = input()->getArgument('stage');
        if (in_array($inputEnv, ['dev','build'])) {
            //dev tasks
            $mainTasks = [
                'deploy:vendors',
                'deploy:clear_paths',
                'deploy:oro:platform:update',
                'deploy:writable',
                'success'
            ];
        }
    }

    foreach ($mainTasks as $task) {
        invoke($task);
    }
})->desc('Deploy steps');
