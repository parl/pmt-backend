<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignedTo extends Model
{
    use HasFactory;
    use Uuids;
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'developing_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function team()
    {
        return $this->belongsTo(Developing::class, 'developing_id');
    }
}
