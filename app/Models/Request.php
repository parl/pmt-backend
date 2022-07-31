<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'client_id',
        'project_name',
        'project_description'
    ];
    public function User()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
