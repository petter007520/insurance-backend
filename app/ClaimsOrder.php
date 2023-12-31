<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class ClaimsOrder extends Model
{
    protected $table="claims_order";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = [];

    public function order(){
        return $this->belongsTo(Productbuy::class,'order_id','id');
    }
}
