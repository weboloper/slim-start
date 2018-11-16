<?php  

namespace App\Models;

class ResetPassword extends Model
{   
     /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'reset_token',
        'expires'
    ];

    // public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $hidden = array('reset_token' );

 
}