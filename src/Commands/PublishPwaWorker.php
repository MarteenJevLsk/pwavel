<?php

namespace Marteen\Pwavel\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PublishPwaWorker extends Command
{
    protected $signature = 'pwavel:publish';
    protected $description = 'Creates a simple PWA support for Laravel Starter Kit.';

    protected Filesystem $files;

    public function __construct()
    {
        parent::__construct();
        $this->files = new Filesystem();
    }

    public function handle()
    {
        $base = base_path();
        $publicPath = $base . '\\public';
        $viewsPath = $base . '\\resources\\views\\partials';

        $this->info("\n  \033[0;44;37m INFO \033[0m Publishing web app support files to \033[1m{$base}\033[0m...\n");

        if (!$this->files->exists($viewsPath)) {
            $this->files->makeDirectory($viewsPath, 0755, true);
            $this->info("  \033[0;44;37m INFO \033[0m Created views folder: \033[1m{$viewsPath}\033[0m\n");
        }

        $resourceStubPath = __DIR__ . '/../resources';
        $stubs = [
            'manifest.stub' => 'manifest.json',
            'robots.stub'   => 'robots.txt',
            'sw.stub'       => 'sw.js',
        ];

        foreach ($stubs as $stub => $target) {
            $source = $resourceStubPath . '\\' . $stub;
            $destination = $publicPath . '\\' . $target;

            if (!file_exists($source)) {
                $this->warn("\n  \033[0;41;37m ERROR \033[0m Stub missing: \033[1m{$source}\033[0m\n");
                continue;
            }

            if ($this->files->exists($destination)) {
                $this->warn("  \033[0;43;37m WARN \033[0m File already exists: \033[1m{$destination}\033[0m\n");
                continue;
            }

            $this->files->copy($source, $destination);
            $this->info("  ✔ [Published] \033[1m{$target}\033[0m.");
        }

        $partialsStubPath = __DIR__ . '/../partials';
        $partials = [
            'metaInf.blade.stub'    => 'metaInf.blade.php',
            'manifest.blade.stub'   => 'manifest.blade.php',
        ];

        foreach ($partials as $stub => $filename) {
            $source = $partialsStubPath . '\\' . $stub;
            $destination = $viewsPath . '\\' . $filename;

            if (!file_exists($source)) {
                $this->warn("\n  \033[0;41;37m ERROR \033[0m Partial stub missing: \033[1m{$source}\033[0m\n");
                continue;
            }

            if ($this->files->exists($destination)) {
                $this->warn("  \033[0;43;37m WARN \033[0m Partial already exists: \033[1m{$destination}\033[0m\n");
                continue;
            }

            $this->files->copy($source, $destination);
            $this->output->progressStart(100);
            for ($i = 0; $i < 100; $i++) {
                usleep(8000);
                $this->output->progressAdvance();
            }
            $this->output->progressFinish();
            $this->info("  ✔ [Partial] \033[1m{$filename}\033[0m created.\n");
        }

        $iconsStubPath = $resourceStubPath . '\\icons';
        $iconsDestPath = $publicPath . '\\icons';

        if (!$this->files->exists($iconsDestPath)) {
            $this->files->makeDirectory($iconsDestPath, 0755, true);
            $this->info("  \033[0;44;37m INFO \033[0m Created folder: \033[1m{$iconsDestPath}\033[0m\n");
        }

        $icons = [
    'icon-192.png',
    'icon-512.png',
];

foreach ($icons as $icon) {
    $source = $iconsStubPath . '\\' . $icon;
    $destination = $iconsDestPath . '\\' . $icon;

    if (!file_exists($source)) {
        $this->warn("\n  \033[0;41;37m ERROR \033[0m Missing icon: \033[1m{$source}\033[0m");
        continue;
    }

    if ($this->files->exists($destination)) {
        $this->warn("  \033[0;43;37m WARN \033[0m Icon already exists: \033[1m{$destination}\033[0m \n");
        continue;
    }

    $this->files->copy($source, $destination);
    for ($i = 0; $i < 50; $i++) {
        $this->output->write('.');
    }
    $this->output->progressStart(100);
    for ($i = 0; $i < 100; $i++) {
        usleep(8000);
        $this->output->progressAdvance();
    }
    $this->output->progressFinish();
    $this->info("  ✔ [Icon] \033[1m{$icon}\033[0m template published.\n");
}

$screenshotSource = $resourceStubPath . '\\screenshot';
$screenshotDest = $publicPath . '\\screenshot';

if (!$this->files->exists($screenshotSource)) {
    $this->warn("\n  \033[0;41;37m ERROR \033[0m Screenshot source folder missing: \033[1m{$screenshotSource}\033[0m\n");
} else {
    if (!$this->files->exists($screenshotDest)) {
        $this->files->makeDirectory($screenshotDest, 0755, true);
        $this->info("  \033[0;44;37m INFO \033[0m Created folder: \033[1m{$screenshotDest}\033[0m\n");
    }

    $images = $this->files->files($screenshotSource);

    foreach ($images as $image) {
        $fileName = $image->getFilename();
        $destFile = $screenshotDest . '/' . $fileName;

        if ($this->files->exists($destFile)) {
            $this->warn("  \033[0;43;37m WARN \033[0m Image already exists: \033[1m{$destFile}\033[0m \n");
            continue;
        }

        $this->files->copy($image->getPathname(), $destFile);
        $this->info("  ✔ [screenshot-template] \033[1m{$fileName}\033[0m template published.\n");
    }
}

        $this->info("\n\033[1;44;37m Published static PWA assets and 2 partials. Use @include('partials.manifest') and @include('partials.metaInf') in your layout. \033[0m\n");
        $this->info("👉 More info: \033[1mhttps://github.com/MarteenJevLsk/pwavel\033[0m\n");

        return Command::SUCCESS;
    }
}
