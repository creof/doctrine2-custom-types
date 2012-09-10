<?php

namespace CrEOF\Tests;

require __DIR__ . '/../../../vendor/autoload.php';

error_reporting(E_ALL | E_STRICT);

$loader = new \Composer\Autoload\ClassLoader();
$loader->add('Doctrine\Tests', __DIR__ . '/../../../vendor/doctrine/orm/tests');
$loader->register();

\Doctrine\DBAL\Types\Type::addType('approx_date', '\CrEOF\DBAL\Types\ApproxDateType');
\Doctrine\DBAL\Types\Type::addType('gender', '\CrEOF\DBAL\Types\GenderType');
