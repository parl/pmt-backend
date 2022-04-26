<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User_team extends Model
{
    use HasFactory;
    use Uuids;
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'team_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function team()
    {
        return $this->belongsTo(User::class, 'team_id');
    }
}
