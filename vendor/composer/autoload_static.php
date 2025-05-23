<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1389de4e30caaee767b129c732fd0651
{
    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'Zigaform\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Zigaform\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Zigaform\\Admin\\List_data' => __DIR__ . '/../..' . '/includes/admin/class-admin-list.php',
        'Zigaform\\Template' => __DIR__ . '/../..' . '/includes/class-template.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1389de4e30caaee767b129c732fd0651::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1389de4e30caaee767b129c732fd0651::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1389de4e30caaee767b129c732fd0651::$classMap;

        }, null, ClassLoader::class);
    }
}
