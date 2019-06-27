<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
	protected $guarded = [];

    public function milestone(){ //$task->milestone

    	return $this->belongsTo('App\Milestone', 'milestone_id');
    }
    public function employee(){ 

    	return $this->belongsTo('App\Employee', 'employee_id');
    }
}
