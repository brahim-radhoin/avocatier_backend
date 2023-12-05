<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Language extends Model
{
    use HasFactory;

    public function avos(): BelongsToMany
    {
        return $this->belongsToMany(avo::class);
    }
}