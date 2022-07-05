<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'task_id',
        'status',
        'type',
    ];
    public function internalBriefing()
    {
        return $this->belongsTo(InternalBriefing::class, 'task_id');
    }
}
