<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6fcf81c944d501beb9e81e7e4423f5a6
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Moment\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Moment\\' => 
        array (
            0 => __DIR__ . '/..' . '/fightbulc/moment/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6fcf81c944d501beb9e81e7e4423f5a6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6fcf81c944d501beb9e81e7e4423f5a6::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
