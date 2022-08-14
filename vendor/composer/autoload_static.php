<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbb168560259c2254e5f4104fc2bce7f9
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'Razorpay\\Tests\\' => 15,
            'Razorpay\\Api\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Razorpay\\Tests\\' => 
        array (
            0 => __DIR__ . '/..' . '/razorpay/razorpay/tests',
        ),
        'Razorpay\\Api\\' => 
        array (
            0 => __DIR__ . '/..' . '/razorpay/razorpay/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'R' => 
        array (
            'Requests' => 
            array (
                0 => __DIR__ . '/..' . '/rmccue/requests/library',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbb168560259c2254e5f4104fc2bce7f9::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbb168560259c2254e5f4104fc2bce7f9::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitbb168560259c2254e5f4104fc2bce7f9::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitbb168560259c2254e5f4104fc2bce7f9::$classMap;

        }, null, ClassLoader::class);
    }
}