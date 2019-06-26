<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
	protected $guarded = [];
    public function project(){ //$milestone->project

    	return $this->belongsTo('App\Project', 'project_id');
    }
    public function responsible(){

    	return $this->belongsTo('App\Employee', 'employee_id');
    }
}
