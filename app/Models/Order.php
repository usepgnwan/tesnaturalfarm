<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Order extends Model
{   
    protected $guarded = ["id"];
    protected static function boot()
    {
        parent::boot(); 
        static::creating(function ($model) {
            $model->no_order = self::generateno_order();
        });
    }

    public static function generateno_order()
    {
        $prefix = 'NFORD';
        $date   = Carbon::now()->format('Ymd'); 
        $last = self::whereDate('created_at', Carbon::today())
            ->orderBy('no_order', 'desc')
            ->first();

        if ($last) { 
            $lastNumber = intval(substr($last->no_order, -3));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . $date . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }
}
