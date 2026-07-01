<?php

namespace App\Console\Commands\Addon;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Exception;
use Log;

class InstallFeeModule extends Command
{
    protected $signature = 'module:install-fee';

    protected $description = 'Installs a custom module from a private Git repository';

    public function handle()
    {
        try{

           // Step 1: Get options or prompt
           // $repo = $this->ask('Enter the repository URL');
            //$package = $this->ask('Enter the composer package name');
            $username = $this->ask('Enter your Git username');
            $token =$this->secret('Enter your Git token');
            $repo='https://github.com/gego-k12/fee.git';
            $package='gegok12/fee:v1.0.x-dev';

            // Step 2: Validate
            $validator = Validator::make([
               // 'repo' => $repo,
               // 'package' => $package,
                'username' => $username,
                'token' => $token,
            ], [
              //  'repo' => ['required', 'url'],
               // 'package' => ['required', 'string'],
                'username' => ['required', 'string'],
                'token' => ['required', 'string'],
            ] ,[
                    //'repo.required' => 'The repository URL is required.',
                   // 'repo.url' => 'The repository URL must be a valid URL.',
                   // 'package.required' => 'The package name is required.',
                    'username.required' => 'Git username is required.',
                    'token.required' => 'Git token is required.',
                ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $this->error($error);
                }
                return Command::FAILURE;
            }

           /* if (!$repo || !$package || !$username || !$token) {
                $this->error('Missing one or more required options.');
                return 1;
            }*/

            // Step 1: Configure global Composer auth
            $cmd = "composer config --global github-oauth.github.com {$token}";
            exec($cmd, $output, $status);
            if ($status !== 0) {
                $this->error("Failed to configure Composer auth: " . implode("\n", $output));
                return 1;
            }

            // Step 2: Add repository to composer.json
            $parsed = parse_url($repo);
            $authUrl = "{$parsed['scheme']}://{$parsed['host']}{$parsed['path']}";
            $composerPath = base_path('composer.json');
            $composer = json_decode(file_get_contents($composerPath), true);

            $alreadyExists = collect($composer['repositories'] ?? [])->contains(fn($r) => $r['url'] === $repo);
            if (!$alreadyExists) {
                $composer['repositories'][] = ['type' => 'vcs', 'url' => $authUrl];
                file_put_contents($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                $this->info("Added repository to composer.json");
            }

            // Step 3: Install package
            exec("composer require {$package}", $output, $status);
            if ($status !== 0) {
                $this->error("Composer require failed: " . implode("\n", $output));
                return 1;
            }
            $this->info("Package installed: {$package}");

            // Step 4: Publish assets
            foreach (['fee-routes', 'fee-components', 'fee-views', 'fee-migrations', 'fee-config', 'feeapi-routes'] as $tag) {
                // Artisan::call('vendor:publish', ['--tag' => $tag, '--force' => true]);
                exec("php artisan vendor:publish --tag='".$tag."' --force", $output);
                $this->info("Published: {$tag}");
            }

            // Step 5: Update route loader
            // $routesPath = base_path('routes/web.php');
            // $customRoutes = [
            //     "if (file_exists(base_path('routes/gfee.php'))) {\n    require base_path('routes/gfee.php');\n}"
            // ];

            // foreach ($customRoutes as $line) {
            //     if (!str_contains(file_get_contents($routesPath), trim($line))) {
            //         file_put_contents($routesPath, "\n{$line}\n", FILE_APPEND);
            //         $this->info("Appended to routes/web.php");
            //     }
            // }

            $customRoutes = [
                "routes/web.php"=>"if (file_exists(base_path('routes/gfee.php'))) {\n    require base_path('routes/gfee.php');\n}",
                "routes/api.php"=>"if (file_exists(base_path('routes/gfeeapi.php'))) {\n    require base_path('routes/gfeeapi.php');\n}"
            ];

            foreach ($customRoutes as $file=>$line) 
            {

                $routesPath = base_path($file);

                if (!str_contains(file_get_contents($routesPath), trim($line))) {
                    file_put_contents($routesPath, "\n{$line}\n", FILE_APPEND);
                    $this->info("Appended to ".$file);
                }
            }

            // Step 6: Add to app.js
            // $appJsPath = resource_path('assets/js/custom_addon.js');
            // if (!str_contains(file_get_contents($appJsPath), "require('./gfees')")) {
            //     file_put_contents($appJsPath, file_get_contents($appJsPath) . "\nrequire('./gfees');\n");
            //     $this->info("Updated app.js");
            // }
            $appJsPath = resource_path('assets/js/custom_addon.js');
            if (!file_exists($appJsPath)) {
                $this->error("custom_addon.js not found!");
                return 1;
            }
            $content = file_get_contents($appJsPath);
            $importLine = "import { registerFees } from './gfees'";

            if (!str_contains($content, $importLine)) {

                if (preg_match('/^import .*$/m', $content)) {

                    $content = preg_replace(
                        '/^import .*$/m',
                        "$0\n".$importLine,
                        $content,
                        1
                    );

                } else {

                    $content = $importLine . "\n\n" . $content;

                }
            }

            if (!str_contains($content, "registerFees(app)")) {

                $content = preg_replace(
                    '/export default function registerCustomAddon\(app\)\s*\{/',
                    "export default function registerCustomAddon(app) {\n\n    registerFees(app)",
                    $content
                );

            }
            file_put_contents($appJsPath, $content);
            $this->info("✔ custom_addon.js updated successfully");


            // Step 7: NPM install/build
            if ($this->confirm('Do you want to run NPM install and build?', true)) {
                exec('npm install', $npmOut, $npmStatus);
                exec('npm run dev', $devOut, $devStatus);
                $this->info("NPM build complete");
            } else {
                $this->warn("Skipped NPM install/build");
            }

            // Step 8: Migrate
            if ($this->confirm('Do you want to run database migrations?', true)) {
                exec("php artisan migrate --force", $output);
                $this->info("Database migrated");
            } else {
                $this->warn("Skipped migrations");
            }


            // Step 9: Seeder
            if ($this->confirm('Do you want to run seeders?', true)) {
                $output = [];
                    $status = 0;

                exec(
                    'php artisan db:seed --class="Gegok12\\Fee\\Database\\Seeders\\FeeGroupTableSeeder" --force 2>&1',
                    $output,
                    $status
                );

                $this->line(implode(PHP_EOL, $output));

                if ($status !== 0) {
                    $this->error("Seeder failed.");
                    return Command::FAILURE;
                }
                $this->info("Table Seeded");
            } else {
                $this->warn("Skipped seeding");
            }

            // Artisan::call('cache:clear');
            // Artisan::call('view:clear');
            // Artisan::call('route:clear');
            // Artisan::call('config:clear');

            $this->info("Module installed successfully!");
            return 0;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }

    }
}
