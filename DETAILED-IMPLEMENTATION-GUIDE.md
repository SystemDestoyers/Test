# Detailed Implementation Guide for Jadco CMS Backend

This document provides a comprehensive, step-by-step guide for implementing the Jadco CMS backend and admin dashboard based on the requirements in BACKEND-PRD.md. Each step includes specific code examples and implementation details.

## Phase 1: Project Setup

### Step 1: Database Configuration

1. Ensure your `.env` file has the correct database configuration:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jadco-new
DB_USERNAME=root
DB_PASSWORD=your_password
```

2. Create the database if it doesn't exist:

```sql
CREATE DATABASE IF NOT EXISTS `jadco-new` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 2: Create Base Models

1. Create the Role model:

```bash
php artisan make:model Role -m
```

Implement the Role model as specified in MODELS-IMPLEMENTATION.md.

2. Update the User model to include role relationship:

Update `app/Models/User.php` to include the role relationship and helper methods as specified in MODELS-IMPLEMENTATION.md.

3. Create the Media model:

```bash
php artisan make:model Media -m
```

Implement the Media model as specified in MODELS-IMPLEMENTATION.md.

4. Create the Page model:

```bash
php artisan make:model Page -m
```

Implement the Page model as specified in MODELS-IMPLEMENTATION.md.

5. Create the Section model:

```bash
php artisan make:model Section -m
```

Implement the Section model as specified in MODELS-IMPLEMENTATION.md.

6. Create the Service model:

```bash
php artisan make:model Service -m
```

Implement the Service model as specified in MODELS-IMPLEMENTATION.md.

### Step 3: Create Migrations

Create the following migrations as specified in DATABASE-MIGRATIONS.md:

1. Create Roles Table:

```bash
php artisan make:migration create_roles_table
```

2. Add Role Column to Users Table:

```bash
php artisan make:migration add_role_id_to_users_table
```

3. Create Media Table:

```bash
php artisan make:migration create_media_table
```

4. Create Pages Table:

```bash
php artisan make:migration create_pages_table
```

5. Create Sections Table:

```bash
php artisan make:migration create_sections_table
```

6. Create Services Table:

```bash
php artisan make:migration create_services_table
```

7. Create Image-Page Pivot Table:

```bash
php artisan make:migration create_image_page_table
```

8. Create Image-Section Pivot Table:

```bash
php artisan make:migration create_image_section_table
```

Implement each migration file according to the specifications in DATABASE-MIGRATIONS.md.

## Phase 2: Service-Repository Pattern Implementation

### Step 4: Create Repository Interfaces

1. Create a base repository interface:

```bash
mkdir -p app/Repositories/Interfaces
```

Create `app/Repositories/Interfaces/RepositoryInterface.php`:

```php
<?php

namespace App\Repositories\Interfaces;

interface RepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
```

2. Create specific repository interfaces for each model:

Create `app/Repositories/Interfaces/UserRepositoryInterface.php`:

```php
<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByEmail($email);
}
```

Create similar interfaces for PageRepositoryInterface, SectionRepositoryInterface, ServiceRepositoryInterface, and MediaRepositoryInterface with any model-specific methods.

### Step 5: Implement Repositories

1. Create a base repository implementation:

```bash
mkdir -p app/Repositories
```

Create `app/Repositories/BaseRepository.php`:

```php
<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}
```

2. Implement specific repositories for each model:

Create `app/Repositories/UserRepository.php`:

```php
<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }
}
```

Implement similar repositories for PageRepository, SectionRepository, ServiceRepository, and MediaRepository with any model-specific methods.

### Step 6: Create Services

1. Create service classes for each model:

```bash
mkdir -p app/Services
```

Create `app/Services/UserService.php`:

```php
<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->all();
    }

    public function getUserById($id)
    {
        return $this->userRepository->find($id);
    }

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }

    public function updateUser($id, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->userRepository->update($id, $data);
    }

    public function deleteUser($id)
    {
        return $this->userRepository->delete($id);
    }
}
```

Implement similar services for PageService, SectionService, ServiceService, and MediaService with any model-specific business logic.

2. Register repositories and services in the service provider:

Update `app/Providers/AppServiceProvider.php`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\Interfaces\PageRepositoryInterface;
use App\Repositories\PageRepository;
use App\Repositories\Interfaces\SectionRepositoryInterface;
use App\Repositories\SectionRepository;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Repositories\ServiceRepository;
use App\Repositories\Interfaces\MediaRepositoryInterface;
use App\Repositories\MediaRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
        $this->app->bind(SectionRepositoryInterface::class, SectionRepository::class);
        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(MediaRepositoryInterface::class, MediaRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
```

## Phase 3: Admin Dashboard Development

### Step 7: Create Admin Controllers

1. Create the base admin controller:

```bash
mkdir -p app/Http/Controllers/Admin
```

Create `app/Http/Controllers/Admin/AdminController.php`:

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
```

2. Create the dashboard controller:

Create `app/Http/Controllers/Admin/DashboardController.php`:

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Services\PageService;
use App\Services\ServiceService;
use App\Services\MediaService;
use App\Services\UserService;

class DashboardController extends AdminController
{
    protected $pageService;
    protected $serviceService;
    protected $mediaService;
    protected $userService;

    public function __construct(
        PageService $pageService,
        ServiceService $serviceService,
        MediaService $mediaService,
        UserService $userService
    ) {
        parent::__construct();
        $this->pageService = $pageService;
        $this->serviceService = $serviceService;
        $this->mediaService = $mediaService;
        $this->userService = $userService;
    }

    public function index()
    {
        $stats = [
            'pages' => $this->pageService->countPages(),
            'services' => $this->serviceService->countServices(),
            'media' => $this->mediaService->countMedia(),
            'users' => $this->userService->countUsers(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
```

3. Create controllers for Page, Service, Media, and User management:

Implement controllers for each entity with CRUD operations using the respective services.

### Step 8: Create Admin Routes

1. Define admin routes in `routes/web.php`:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Pages
    Route::resource('pages', PageController::class);
    
    // Sections
    Route::post('pages/{page}/sections', [PageController::class, 'storeSection'])->name('pages.sections.store');
    Route::put('pages/{page}/sections/{section}', [PageController::class, 'updateSection'])->name('pages.sections.update');
    Route::delete('pages/{page}/sections/{section}', [PageController::class, 'destroySection'])->name('pages.sections.destroy');
    
    // Services
    Route::resource('services', ServiceController::class);
    
    // Media
    Route::resource('media', MediaController::class);
    
    // Users
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
```

2. Implement middleware for role-based access control:

Create `app/Http/Middleware/CheckRole.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user() || !$request->user()->hasRole($role)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
```

Register the middleware in `app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
    // Other middleware...
    'role' => \App\Http\Middleware\CheckRole::class,
];
```

### Step 9: Create Blade Views

1. Create layout templates for admin dashboard:

```bash
mkdir -p resources/views/admin/layouts
```

Create `resources/views/admin/layouts/app.blade.php`:

```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Jadco CMS') }} - Admin</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('admin.layouts.navigation')

        <!-- Page Heading -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                @yield('header')
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</body>
</html>
```

2. Create views for each section (dashboard, pages, services, media, users):

Implement views for each entity with forms for CRUD operations.

## Phase 4: API Development

### Step 10: Create API Controllers

1. Create API controllers for Page, Service, and Media:

```bash
mkdir -p app/Http/Controllers/Api
```

Create `app/Http/Controllers/Api/PageController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PageService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function show($slug)
    {
        $page = $this->pageService->getPageBySlug($slug);
        
        if (!$page) {
            return response()->json(['error' => 'Page not found'], 404);
        }
        
        return response()->json($page);
    }
}
```

Implement similar controllers for ServiceController and MediaController.

### Step 11: Define API Routes

1. Define API routes in `routes/api.php`:

```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\MediaController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes
Route::get('/pages/{slug}', [PageController::class, 'show']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::get('/media', [MediaController::class, 'index']);

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/media', [MediaController::class, 'store']);
    Route::delete('/media/{id}', [MediaController::class, 'destroy']);
});
```

## Phase 5: Seeders and Data Population

### Step 12: Create Seeders

1. Create RoleSeeder:

```bash
php artisan make:seeder RoleSeeder
```

Implement `database/seeders/RoleSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'editor', 'viewer'];
        
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
```

2. Create AdminSeeder:

```bash
php artisan make:seeder AdminSeeder
```

Implement `database/seeders/AdminSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('111'),
            'role_id' => $adminRole->id,
        ]);
    }
}
```

3. Create PageSeeder, SectionSeeder, ServiceSeeder, and MediaSeeder:

Implement seeders to extract content from the existing frontend.

### Step 13: Run Migrations and Seeders

1. Run migrations to create database tables:

```bash
php artisan migrate
```

2. Run seeders to populate database with initial data:

```bash
php artisan db:seed
```

## Phase 6: Testing and Deployment

### Step 14: Test Admin Dashboard

1. Test CRUD operations for all entities:
   - Create, read, update, and delete operations for pages, sections, services, media, and users.

2. Test role-based access control:
   - Verify that users with different roles have appropriate access to different parts of the admin dashboard.

### Step 15: Test API Endpoints

1. Test all API endpoints for correct functionality:
   - GET /api/pages/{slug}
   - GET /api/services
   - GET /api/services/{id}
   - GET /api/media
   - POST /api/media
   - DELETE /api/media/{id}

2. Verify data integrity and relationships:
   - Ensure that relationships between models are correctly maintained.

### Step 16: Deploy to Production

1. Configure production environment:
   - Set up production database.
   - Configure environment variables.
   - Set up file storage.

2. Deploy application to production server:
   - Upload application files.
   - Run migrations and seeders.
   - Configure web server.

## Conclusion

This detailed implementation guide provides a comprehensive roadmap for implementing the Jadco CMS backend and admin dashboard. By following these steps, you will create a robust CMS system with a clean architecture based on the Service-Repository pattern, which will be maintainable and scalable for future enhancements.