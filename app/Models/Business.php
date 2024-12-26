<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'name',
        'site_web',
        'date_promotion_end',
        'email_contact',
        'phone_contact',
        'slogan',
        'slug',
        'address',
        'description',
        'latitude',
        'longitude',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
