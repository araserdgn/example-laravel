<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;
    protected $fillable = ['name','completed','user_id','slug'];

    protected $casts = [ //!cats
        'completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //!SCOPE
    // kullanıcı todoları listele
    public function scopeUserTodo($query, $userId) //! Scope
    {
        return $query->where('user_id', $userId);
    }


    public function scopeCompletedTodo($query) //! scope
    {
        return $query->where('completed', false);
    }


    //!Accessor
    // name büyük harfle alcaz
    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

     // name küçült Mutator
     public function setNameAttribute($value)
     {
         $this->attributes['name'] = strtolower($value);
     }


}
