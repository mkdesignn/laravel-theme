<?php
Route::group(["middleware"=>"auth"], function(){

    Route::group(["middleware"=>"user:admin"], function(){

        Route::group(["prefix"=>"panel"], function(){

            // all routes goes here
            Route::get("log", "LogController@getIndex")->name("log");

            Route::get("user", "UserController@getIndex")->name("user.index");
            Route::get("user/create", "UserController@getCreate")->name("user.create");
            Route::get("user/edit/{id}", "UserController@getEdit")->name("user.edit");
            Route::get("user/delete/{id}", "UserController@getDelete")->name("user.delete");
            Route::post("user/store", "UserController@postStore")->name("user.store");

            Route::get("config", "ConfigController@getIndex")->name("config.index");
            Route::post("config", "ConfigController@postStore")->name("config.store");
        });

        Route::get("panel", "PanelController@getIndex")->name("panel.index");
    });
});

Auth::routes();

