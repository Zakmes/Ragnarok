<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class HelperServiceProvider
 *
 * @package App\Providers
 */
class HelperServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $recursiveDirectoryIterator = new RecursiveDirectoryIterator($this->getHelperDirectoryPath());
        $iterator = new RecursiveIteratorIterator($recursiveDirectoryIterator);

        while ($iterator->valid()) {
            if ($this->isHelperFile($iterator)) {
                require $iterator->key();
            }

            $iterator->next();
        }
    }

    private function getHelperDirectoryPath()
    {
        return app_path('Support' . DIRECTORY_SEPARATOR . 'Helpers');
    }

    private function isHelperFile(RecursiveIteratorIterator $iterator): bool
    {
        return ! $iterator->isDot()
            && $iterator->isFile()
            && $iterator->isReadable()
            && $iterator->current()->getExtension() === 'php'
            && strpos($iterator->current()->getFilename(), 'Helper');
    }
}
