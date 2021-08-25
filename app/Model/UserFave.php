<?php

declare (strict_types=1);

namespace App\Model;

/**
 */
class UserFave extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_faves';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
