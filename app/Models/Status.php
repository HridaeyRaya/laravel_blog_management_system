<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'statusable_type', 'statusable_id'];

    public function statusable(): MorphTo
    {
        return $this->morphTo();
    }
}
