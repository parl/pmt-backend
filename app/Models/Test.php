<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Test extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'test_case',
        'priority',
        'status',
        'project_id',
        'description',
        'components',
        'steps_to_reproduce',
        'result',
        'expected_result',
        'attachment',
        'type',
    ];
    public function Project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
