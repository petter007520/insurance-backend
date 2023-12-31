<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class HealthExpense extends Model
{
    protected $table="health_expense";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = [];

}
