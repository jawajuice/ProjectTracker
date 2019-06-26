<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timetracking extends Model
{

	protected $guarded = [];
    public function milestone(){

    	return $this->belongsTo(Milestone::class);
    }
}
