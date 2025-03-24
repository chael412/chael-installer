<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class MakeOrionController extends Command
{
    protected $signature = 'make:orion {name}';
    protected $description = 'Generate a new Orion controller, model, and migration if not exists';

    public function handle()
    {
        $name = str_replace('\\', '/', $this->argument('name')); // Normalize path
        $path = app_path("Http/Controllers/Orion/{$name}.php");

        // Extract directory path and class name
        $directory = dirname($path);
        $className = basename($name);
        $modelName = Str::singular(str_replace('Controller', '', class_basename($className)));

        // Ensure the directory exists
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        // Define namespace dynamically
        $namespace = "App\\Http\\Controllers\\Orion";
        $namespace = rtrim($namespace, '\\');

        // Orion controller template
        $controllerStub = <<<PHP
        <?php

        namespace {$namespace};

        use Orion\Http\Controllers\Controller;
        use App\Models\\{$modelName};

        class {$className} extends Controller
        {
            protected \$model = {$modelName}::class;
        }
        PHP;

        // Create the controller file
        if (!File::exists($path)) {
            File::put($path, $controllerStub);
            $this->info("✅ Orion controller created: app/Http/Controllers/Orion/{$name}.php");
        } else {
            $this->error("⚠️ Controller already exists!");
        }

        // **Check & Create Model if Not Exists**
        $modelPath = app_path("Models/{$modelName}.php");

        if (!File::exists($modelPath)) {
            // Model template
            $modelStub = <<<PHP
            <?php

            namespace App\Models;

            use Illuminate\Database\Eloquent\Factories\HasFactory;
            use Illuminate\Database\Eloquent\Model;

            class {$modelName} extends Model
            {
                use HasFactory;

                protected \$guarded = [];
            }
            PHP;

            // Create the model file
            File::put($modelPath, $modelStub);
            $this->info("✅ Model created: app/Models/{$modelName}.php");
        } else {
            $this->info("✅ Model already exists: app/Models/{$modelName}.php");
        }

        // **Create Migration (Check by Name, Ignore Timestamp)**
        $tableName = Str::plural(Str::snake($modelName));
        $migrationName = "create_{$tableName}_table";

        // Check if migration with the same name (ignoring timestamp) already exists
        $migrationPath = database_path('migrations');
        $existingMigrations = collect(File::files($migrationPath))
            ->filter(fn($file) => Str::contains($file->getFilename(), $migrationName))
            ->count();

        $migrationCreated = false;

        if ($existingMigrations === 0) {
            $timestamp = now()->format('Y_m_d_His');
            $fullMigrationName = "{$timestamp}_{$migrationName}.php";
            $fullMigrationPath = $migrationPath . "/{$fullMigrationName}";

            $migrationStub = <<<PHP
            <?php

            use Illuminate\Database\Migrations\Migration;
            use Illuminate\Database\Schema\Blueprint;
            use Illuminate\Support\Facades\Schema;

            return new class extends Migration
            {
                public function up(): void
                {
                    Schema::create('{$tableName}', function (Blueprint \$table) {
                        \$table->id();
                        \$table->timestamps();
                    });
                }

                public function down(): void
                {
                    Schema::dropIfExists('{$tableName}');
                }
            };
            PHP;

            // Create the migration file
            File::put($fullMigrationPath, $migrationStub);
            $this->info("✅ Migration created: database/migrations/{$fullMigrationName}");
            $migrationCreated = true;
        } else {
            $this->info("✅ Migration for '{$tableName}' already exists.");
        }

        // **Add Route to api.php**
        $routePath = base_path('routes/api.php');
        $routeName = Str::plural(Str::kebab($modelName)); // Plural and kebab-case
        $routeEntry = "\nOrion::resource('{$routeName}', \\{$namespace}\\{$className}::class)->middleware(['auth','web']);";

        // Ensure `api.php` exists
        if (!File::exists($routePath)) {
            File::put($routePath, "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\nuse Orion\\Facades\\Orion;\n\n" . $routeEntry);
            $this->info("✅ Created api.php with Orion route.");
        } else {
            $routes = File::get($routePath);

            // **Insert Orion Import if Missing**
            $orionImport = "use Orion\\Facades\\Orion;";
            $routeImport = "use Illuminate\\Support\\Facades\\Route;";

            if (!Str::contains($routes, $orionImport)) {
                // Insert Orion import **after** Route import
                if (Str::contains($routes, $routeImport)) {
                    $updatedRoutes = str_replace(
                        $routeImport,
                        $routeImport . "\n" . $orionImport,
                        $routes
                    );

                    File::put($routePath, $updatedRoutes);
                    $this->info("✅ Added Orion import to api.php.");
                } else {
                    // Fallback: Add both imports if Route import is missing
                    File::put($routePath, "<?php\n\n{$routeImport}\n{$orionImport}\n\n" . $routes);
                    $this->info("✅ Added both Route and Orion imports to api.php.");
                }
            }

            // **Add the Route Entry**
            if (!Str::contains($routes, $routeEntry)) {
                File::append($routePath, "\n" . $routeEntry);
                $this->info("✅ Route added to api.php: Orion::resource('{$routeName}', \\{$namespace}\\{$className}::class)->middleware(['auth','web']);");
            } else {
                $this->error("⚠️ Route already exists in api.php");
            }
        }

        // **Ask the user if they want to run migrations**
        if ($migrationCreated) {
            if ($this->confirm('Do you want to run php artisan migrate now?', true)) {
                $this->info("⏳ Running migrations...");
                $process = new Process(['php', 'artisan', 'migrate']);
                $process->run();

                if ($process->isSuccessful()) {
                    $this->info("✅ Migrations executed successfully.");
                } else {
                    $this->error("❌ Failed to run migrations: " . $process->getErrorOutput());
                }
            } else {
                $this->info("⚠️ Skipping migrations.");
            }
        }
    }
}
