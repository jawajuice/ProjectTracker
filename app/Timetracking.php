<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timetracking extends Model
{

	protected $guarded = [];
    public function milestone(){

    	return $this->belongsTo('App\Milestone', 'milestone_id');
    }
    public function employee(){ 

    	return $this->belongsTo('App\Employee', 'employee_id');
    }
}
