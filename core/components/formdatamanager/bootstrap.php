<?php
/**
 * @var \MODX\Revolution\modX $modx
 * @var array $namespace
 */

require_once $namespace['path'] . 'vendor/autoload.php';

$modx->addPackage('FormDataManager', $namespace['path'] . 'src/', null, 'FormDataManager\\');

$modx->services->add('FormDataManager', function($c) use ($modx) {
    return new FormDataManager\FormDataManager($modx);
});
