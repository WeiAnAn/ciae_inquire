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

	Route::patch('/user',['uses'=>'UserController@update']);
	Route::get('/user',['uses'=>'UserController@index']);

	Route::get('/graduate_threshold',['uses'=>'user\GraduateThresholdController@index']);
	Route::get('/graduate_threshold/{id}',['uses'=>'user\GraduateThresholdController@edit']);
	Route::patch('/graduate_threshold/{id}',['uses'=>'user\GraduateThresholdController@update']);

	Route::get('/foreign_language_class',['uses'=>'user\ForeignLanguageClassController@index']);
	Route::post('/foreign_language_class',['uses' => 'ForeignLanguageClassController@insert']);
	Route::get('/foreign_language_class/search',['uses' => 'ForeignLanguageClassController@search']);
	// Route::delete('foreign_language_class/{id}',function(){return view()})

	//教師、研究員專區

	Route::get('/prof_attend_conference',['uses'=>'prof\ProfAttendConferenceController@index']);
	Route::post('prof_attend_conference',['uses' =>'prof\ProfAttendConferenceController@insert']);
	
	Route::get('/foreign_prof_vist',['uses'=>'prof\ForeignProfVistController@index']);

	Route::get('/prof_exchange',['uses'=>'prof\ProfExchangeController@index']);
	
	Route::get('/prof_foreign_research',['uses'=>'prof\ProfForeignResearchController@index']);

	//學生專區

	Route::get('/stu_attend_conf',['uses'=>'stu\StuAttendConfController@index']);


	Route::get('/stu_to_partner_school',['uses'=>'stu\StuToPartnerSchoolController@index']);


	Route::get('/stu_foreign_research',['uses'=>'stu\StuForeignResearchController@index']);


	Route::get('/stu_from_partner_school',['uses'=>'stu\StuFromPartnerSchoolController@index']);


	/*Route::get('/stu_from_partner_school',function(){
		$data=['name'=>'asd'];
		return view('stu/stu_from_partner_school',$data);
	});*/

	Route::get('/short_term_foreign_stu',['uses'=>'stu\ShortTermForeignStuController@index']);


	Route::get('/foreign_stu',['uses'=>'stu\ForeignStuController@index']);

	//其他國際交流活動
	Route::get('/transnational_degree',['uses'=>'other\TransnationalDegreeController@index']);

	Route::get('/partner_school',['uses'=>'other\PartnerSchoolController@index']);

	Route::get('/cooperation_proj',['uses'=>'other\CooperationProjController@index']);
	
	Route::get('internationalize_activity',['uses'=>'other\InternationalizeActivityController@index']);

	
	

});
