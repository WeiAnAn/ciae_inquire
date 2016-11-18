<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'Auth\LoginController@showLoginForm');

Auth::routes();

Route::group(['middleware' => 'auth'],function(){
	Route::get('/home',['as' => 'home', 'uses' => 'HomeController@index']);
	
	Route::get('/user',['uses'=>'UserController@index']);
	Route::post('/user', ['uses'=>'UserController@update']);

	Route::get('/graduate_threshold',function(){
		return view('/user/graduate_threshold');
	});

	Route::get('/foreign_language_class',function(){
		return view('/user/foreign_language_class');
	});
	Route::post('/foreign_language_class',['uses' => 'ForeignLanguageClassController@insert']);
	Route::get('/foreign_language_class/search',['uses' => 'ForeignLanguageClassController@search']);

	Route::get('/prof_attend_conference',function(){
		return view('prof/prof_attend_conference');
	});

	Route::get('/foreign_prof_vist',function(){
		return view('prof/foreign_prof_vist');
	});

	Route::get('/prof_exchange',function(){
		return view('prof/prof_exchange');
	});

	Route::get('/prof_foreign_research',function(){
		return view('prof/prof_foreign_research');
	});

	Route::get('/stu_attend_conf',function(){
		return view('stu/stu_attend_conf');
	});

	Route::get('/stu_to_partner_school',function(){
		return view('stu/stu_to_partner_school');
	});

	Route::get('/stu_foreign_research',function(){
		return view('stu/stu_foreign_research');
	});

	Route::get('/stu_from_partner_school',function(){
		return view('stu/stu_from_partner_school');
	});

	Route::get('/short_term_foreign_stu',function(){
		return view('stu/short_term_foreign_stu');
	});

	Route::get('/cooperation_proj',function(){
		return view('cooperation_proj');
	});
	
	Route::get('/foreign_stu',function(){
		return view('foreign_stu');
	});
	Route::get('/partner_school',function(){
		return view('partner_school');
	});
	
	
	
	
	
	
	
	Route::get('/transnational_degree',function(){
		return view('transnational_degree');
	});

});

Route::get('/test',function(){
	return view('layouts/select');
});
