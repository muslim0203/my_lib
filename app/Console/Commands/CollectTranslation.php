<?php

namespace App\Console\Commands;

use Symfony\Component\Finder\Finder;
use Illuminate\Console\Command;

class CollectTranslation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translation:collect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect all translations from PHP and Blade files';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $languages = ['en'];

        $folders = [
            base_path('app'),
            base_path('database'),
            base_path('resources'),
            base_path('routes'),
            base_path('public'),
        ];

        $finder = new Finder();
        foreach ($folders as $folder) {
            $finder->files()
                ->in($folder)
                ->name('*.php')
                ->name('*.blade.php');  // Blade fayllarni ham qidirish
        }

        $phpFiles = [];
        foreach ($finder as $file) {
            $phpFiles[] = $file->getRealPath();
        }

        if (!is_dir(base_path('lang'))) {
            mkdir(base_path('lang'), 0777, true);
        }

        foreach ($languages as $language) {
            if (!is_dir(base_path('lang/' . $language))) {
                mkdir(base_path('lang/' . $language), 0777, true);
            }
        }

        $translations = [];
        foreach ($phpFiles as $file) {
            $content = file_get_contents($file);

            // 1. Collect from __('...')
            preg_match_all("/__\(['\"](.*?)['\"]\)/", $content, $matches1);

            // 2. Collect from @lang('...')
            preg_match_all("/@lang\(['\"](.*?)['\"]\)/", $content, $matches2);

            $matches = array_merge($matches1[1], $matches2[1]);

            foreach ($matches as $key) {
                $parts = explode('.', $key);
                $fileName = array_shift($parts);
                $finalKey = implode('.', $parts);

                if (!isset($translations[$fileName])) {
                    $translations[$fileName] = [];
                }
                $translations[$fileName][$finalKey] = $finalKey;
            }
        }

        foreach ($languages as $item) {
            foreach ($translations as $fileName => $keys) {
                $path = base_path("lang/{$item}/{$fileName}.php");
                if (!file_exists($path)) {
                    file_put_contents($path, "<?php\n\nreturn " . var_export($keys, true) . ";\n");
                } else {
                    $existingKeys = include $path;
                    try {
                        $mergedKeys = array_unique(array_merge($existingKeys, $keys));
                    } catch (\Exception $exception) {
                        $mergedKeys = array_merge($existingKeys, $keys);
                    }
                    file_put_contents($path, "<?php\n\nreturn " . var_export($mergedKeys, true) . ";\n");
                }
            }
        }

        $this->info('Translations collected successfully!');
    }
}
