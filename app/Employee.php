<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
	protected $guarded = [];
    public function project(){ //$project->employee

    	return $this->belongsTo(Project::class);
    }
}
