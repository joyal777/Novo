<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserApiKey extends Model
{
    protected $table = 'user_api_keys'; // The table name (if it's different from the plural of the model name)

    protected $fillable = [
        'user_id',
        'api_key',
        'api_name',
    ];

    // Define the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
