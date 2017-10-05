<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{ 
 
 	protected $table = "tags";
 
 	protected $fillable = ['parent_id' ,'name' ,'slug' ,'description' ,'type' ]; 
 }