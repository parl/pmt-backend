<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Uuids;
    use HasFactory;
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'description',
        'id_team',
        'PIC_id',
        'progress',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'PIC_id');
    }
}
