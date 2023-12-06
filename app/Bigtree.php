<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bigtree extends Model
{
    protected $table="bigtree";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['nl'];


}
