<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{ 
 
 	protected $table = "posts";
 
 	protected $fillable = ['parent_id' ,'title' ,'content' ,'type' ,'status_id' ,'slug' ,'visible' ,'thumbnail' ,'article' ,'publish_time' ,'keywords' ,'description' ,'author_id' ]; 
 }