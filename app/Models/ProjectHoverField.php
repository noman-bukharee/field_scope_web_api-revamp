<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class ProjectHoverField extends Pivot
{
    protected $table = "project_hover_fields";

    protected $dates = ['created_at','updated_at','deleted_at'];

    protected $fillable = [
        'hover_field_id',
        'project_id',
        'value',
    ];

    public function hover_field(){
        return $this->belongsTo('App\Models\HoverField','hover_field_id' , 'id');
    }

    public function ref_tags(){
        return $this->belongsTo('App\Models\Project','project_id' , 'id');
    }
}
