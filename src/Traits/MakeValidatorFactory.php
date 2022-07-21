<?php

declare(strict_types=1);

namespace TrueLayer\Traits;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;

trait MakeValidatorFactory
{
    /**
     * Build the validation factory
     * Used for validating api requests and responses.
     */
    private function makeValidatorFactory(): ValidatorFactory
    {
        $filesystem = new Filesystem();
        $langPath = \dirname(__FILE__, 3) . '/lang';
        $loader = new \Illuminate\Translation\FileLoader($filesystem, $langPath);
        $loader->addNamespace('lang', $langPath);
        $loader->load('en', 'validation', 'lang');
        $translationFactory = new Translator($loader, 'en');

        return new Factory($translationFactory);
    }
}
