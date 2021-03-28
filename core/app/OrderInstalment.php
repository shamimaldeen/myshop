<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderInstalment extends Model
{
    use SoftDeletes;

    protected $table = 'order_instalments';

    protected $guarded = [''];
    //protected $fillable = ['custom','order_id','customer_id','instalment_id','total_amount'];

    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }

    public function instalment()
    {
        return $this->belongsTo(Instalment::class,'instalment_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }

}
