<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'description',
        'id_team',
        'PIC_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'PIC_id');
    }
    public function team()
    {
        return $this->belongsTo(Team::class, 'id_team');
    }
}
