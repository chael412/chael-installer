<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeInertiaComponent extends Command
{
    protected $signature = 'make:inertia {name}
                            {--c|--components : Create in Components}
                            {--l|--layouts : Create in Layouts}
                            {--s|--sections : Create in Sections}
                            {--p|--pages : Create in Pages}';

    protected $description = 'Create a new React component with optional folder flag or path';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $filesystem = new Filesystem();

        // Extract folder and component name correctly
        $folder = $this->getFolder($name);
        $componentName = basename($name);

        // Determine the final path properly
        $componentPath = resource_path("js/{$folder}/{$name}.jsx");

        // Prevent duplicate folder nesting
        $cleanPath = str_replace("/{$folder}/{$folder}", "/{$folder}", $componentPath);

        if ($filesystem->exists($cleanPath)) {
            $this->error("⚠️ Component {$componentName} already exists in {$folder}!");
            return;
        }

        $filesystem->ensureDirectoryExists(dirname($cleanPath));
        $filesystem->put($cleanPath, $this->getComponentTemplate($componentName));

        $this->info("✅ React component created successfully at: {$cleanPath}");
        $this->openFile($cleanPath);
    }

    /**
     * Determine the target folder (flag or path-based)
     */
    protected function getFolder($name)
    {
        if ($this->option('components')) {
            return 'Components';
        } elseif ($this->option('layouts')) {
            return 'Layouts';
        } elseif ($this->option('sections')) {
            return 'Sections';
        } elseif ($this->option('pages')) {
            return 'Pages';
        }

        // If no flag is provided, use the first part of the path as folder
        $parts = explode('/', $name);
        return (count($parts) > 1) ? $parts[0] : 'Components';
    }

    /**
     * Generate the component template with the correct name
     */
    protected function getComponentTemplate($name)
    {
        return <<<JSX
import React from 'react';

const {$name} = () => {
    return (
        <div>
            {/* {$name} component */}
        </div>
    );
};

export default {$name};
JSX;
    }

    /**
     * Open the created file in the default editor
     */
    protected function openFile($filePath)
    {
        if (PHP_OS_FAMILY === 'Windows') {
            exec("start \"\" \"$filePath\"");
        } elseif (PHP_OS_FAMILY === 'Linux') {
            exec("xdg-open \"$filePath\"");
        } elseif (PHP_OS_FAMILY === 'Darwin') { // macOS
            exec("open \"$filePath\"");
        }
    }
}
