<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
    ];

    public function user_team()
    {
        return $this->hasMany(User_team::class, 'team_id');
    }
    public function Project()
    {
        return $this->hasMany(Project::class, 'id_team');
    }
}
