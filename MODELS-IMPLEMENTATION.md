# Models Implementation for Jadco CMS

This document provides the detailed implementation of the models for the Jadco CMS backend as specified in the BACKEND-PRD.md, including their relationships.

## Model Classes

### 1. User Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the media that were uploaded by the user.
     */
    public function uploadedMedia()
    {
        return $this->hasMany(Media::class, 'uploaded_by');
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if the user is an editor.
     */
    public function isEditor(): bool
    {
        return $this->hasRole('editor');
    }

    /**
     * Check if the user is a viewer.
     */
    public function isViewer(): bool
    {
        return $this->hasRole('viewer');
    }
}
```

### 2. Role Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the users for the role.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
```

### 3. Media Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'media';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'file_name',
        'file_path',
        'file_type',
        'uploaded_by',
    ];

    /**
     * Get the user that uploaded the media.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the pages that use this media.
     */
    public function pages()
    {
        return $this->belongsToMany(Page::class, 'image_page');
    }

    /**
     * Get the sections that use this media.
     */
    public function sections()
    {
        return $this->belongsToMany(Section::class, 'image_section');
    }

    /**
     * Get the services that use this media as their main image.
     */
    public function services()
    {
        return $this->hasMany(Service::class, 'image_id');
    }
}
```

### 4. Page Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'title',
        'content',
    ];

    /**
     * Get the sections for the page.
     */
    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }

    /**
     * Get the images for the page.
     */
    public function images()
    {
        return $this->belongsToMany(Media::class, 'image_page', 'page_id', 'media_id');
    }
}
```

### 5. Section Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'page_id',
        'title',
        'content',
        'order',
    ];

    /**
     * Get the page that owns the section.
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get the images for the section.
     */
    public function images()
    {
        return $this->belongsToMany(Media::class, 'image_section', 'section_id', 'media_id');
    }
}
```

### 6. Service Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'image_id',
    ];

    /**
     * Get the image associated with the service.
     */
    public function image()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }
}
```

## Next Steps After Model Implementation

After implementing the models with their relationships, proceed with:

1. Creating repository interfaces and implementations
2. Developing service classes that use the repositories
3. Building controllers that use the services
4. Creating seeders to populate the database
5. Implementing the admin dashboard views