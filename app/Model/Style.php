<?php

declare (strict_types=1);

namespace App\Model;

/**
 */
class Style extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'styles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'big_picture', 'small_picture'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
}
