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
    /**
     * Method for booting application services.
     *
     * @return void
     */
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

    /**
     * Method for getting the helper file directory.
     *
     * @return string
     */
    private function getHelperDirectoryPath(): string
    {
        return app_path('Support' . DIRECTORY_SEPARATOR . 'Helpers');
    }

    /**
     * Function for determining if the file is an helper file or not.
     *
     * @param  RecursiveIteratorIterator $iterator
     * @return bool
     */
    private function isHelperFile(RecursiveIteratorIterator $iterator): bool
    {
        return ! $iterator->isDot()
            && $iterator->isFile()
            && $iterator->isReadable()
            && $iterator->current()->getExtension() === 'php'
            && strpos($iterator->current()->getFilename(), 'Helper');
    }
}
