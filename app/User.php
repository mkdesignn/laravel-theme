<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{ 
 
 	protected $table = "users";
 
 	protected $fillable = ['name' ,'family' ,'email' ,'username' ,'password' ,'type' ,'role_id' ,'sex' ,'avatar' ,'status' ,'remember_token' ]; 
 
 
            /**
             * @return \Illuminate\Database\Eloquent\Relations\HasMany
             */
            public function roles(){
                return $this->hasMany("App\Role", "id", "role_id");
            }

            /**
             * @param $query
             * @param $parameters
             * @param $value
             */
            public function scopeSearch($query, $parameters, $value){

                foreach( $parameters as $key => $parameter ){
                    if( $key == 0 )
                        $query->where($parameter[0], "LIKE", "%".$value."%");
                    else
                        $query->orWhere($parameter[0], "LIKE", "%".$value."%");

                }
            } 
 
  
 }