<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Developing extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'task_id',
        'start_date',
        'end_date',
        'priority',
        'status',
    ];
    public function internalBriefing()
    {
        return $this->belongsTo(InternalBriefing::class, 'task_id');
    }
    public function AssignedTo()
    {
        return $this->hasMany(AssignedTo::class, 'developing_id');
    }
}
