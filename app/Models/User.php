<?php  

namespace App\Models;

class User extends Model
{   
     /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'name',
        'password', 
        'token', 
        'level', 
        'activated', 
        'remember_identifier', 
        'remember_token', 
    ];

    protected $hidden = array('password', 'token', 'remember_identifier', 'remember_token');

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    public function setPassword($password)
    {   
        $this->update([
            'password' =>    password_hash( $password , PASSWORD_DEFAULT) 
        ]);
    }

    public function resetPassword()
    {
        return $this->hasOne('App\Models\ResetPassword');
        // return $this->hasOne('App\Models\ResetPassword', 'user_id', 'id');
    }

}