<?php


namespace App\Model;


use Carbon\Carbon;
use Hyperf\Database\Model\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'default';

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

    protected function style(): \Hyperf\Database\Model\Relations\BelongsTo
    {
        return $this->belongsTo(Style::class);
    }

    protected function productType(): \Hyperf\Database\Model\Relations\BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }
}
