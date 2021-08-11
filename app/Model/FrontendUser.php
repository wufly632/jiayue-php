<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id 
 * @property string $name 
 * @property string $mobile 
 * @property string $password 
 * @property string $avatar 
 * @property int $wallet 
 * @property string $birth 
 * @property string $gender 
 * @property string $email 
 * @property string $remember_token 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class FrontendUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'frontend_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'wallet' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}