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

		//單位資料修改

	Route::patch('/user',['uses'=>'UserController@update']);
	Route::get('/user',['uses'=>'UserController@index']);

		//英檢畢業門檻
	
	Route::get('/graduate_threshold',['uses'=>'user\GraduateThresholdController@index']);
	Route::post('/graduate_threshold',['uses'=>'user\GraduateThresholdController@insert']);
	Route::get('/graduate_threshold/search',['uses'=>'user\GraduateThresholdController@search']);
	Route::get('/graduate_threshold/{id}',['uses'=>'user\GraduateThresholdController@edit']);
	Route::patch('/graduate_threshold/{id}',['uses'=>'user\GraduateThresholdController@update']);
	Route::delete('/graduate_threshold/{id}',['uses'=>'user\GraduateThresholdController@delete']);

		//全外語授課課程

	Route::get('/foreign_language_class',['uses'=>'user\ForeignLanguageClassController@index']);
	Route::post('/foreign_language_class',['uses'=>'user\ForeignLanguageClassController@insert']);
	Route::get('/foreign_language_class/search',['uses' => 'user\ForeignLanguageClassController@search']);
	Route::get('/graduate_threshold/{id}',['uses'=>'user\ForeignLanguageClassController@edit']);
	Route::patch('/graduate_threshold/{id}',['uses'=>'user\ForeignLanguageClassController@update']);
	Route::delete('/graduate_threshold/{id}',['uses'=>'user\ForeignLanguageClassController@delete']);

	// Route::delete('foreign_language_class/{id}',function(){return view()})

	//教師、研究員專區
	
		//本校教師赴國外出席國際會議

	Route::get('/prof_attend_conference',['uses'=>'prof\ProfAttendConferenceController@index']);
	Route::post('/prof_attend_conference',['uses'=>'prof\ProfAttendConferenceController@insert']);	
	Route::get('/prof_attend_conference/search',['uses' => 'prof\ProfAttendConferenceController@search']);
	Route::get('/prof_attend_conference/{id}',['uses'=>'prof\ProfAttendConferenceController@edit']);
	Route::patch('/prof_attend_conference/{id}',['uses'=>'prof\ProfAttendConferenceController@update']);
	Route::delete('/prof_attend_conference/{id}',['uses'=>'prof\ProfAttendConferenceController@delete']);

	
		//本校教師赴國外交換

	Route::get('/prof_exchange',['uses'=>'prof\ProfExchangeController@index']);
	Route::post('/prof_exchange',['uses'=>'prof\ProfExchangeController@insert']);
	Route::get('/prof_exchange/search',['uses' => 'prof\ProfExchangeController@search']);
	Route::get('/prof_exchange/{id}',['uses'=>'prof\ProfExchangeController@edit']);
	Route::patch('/prof_exchange/{id}',['uses'=>'prof\ProfExchangeController@update']);
	Route::delete('/prof_exchange/{id}',['uses'=>'prof\ProfExchangeController@delete']);


		//本校教師赴國外交換

	Route::get('/prof_foreign_research',['uses'=>'prof\ProfForeignResearchController@index']);
	Route::post('/prof_foreign_research',['uses'=>'prof\ProfForeignResearchController@insert']);
	Route::get('/prof_foreign_research/search',['uses' => 'prof\ProfForeignResearchController@search']);
	Route::get('/prof_foreign_research/{id}',['uses'=>'prof\ProfForeignResearchController@edit']);
	Route::patch('/prof_foreign_research/{id}',['uses'=>'prof\ProfForeignResearchController@update']);
	Route::delete('/prof_foreign_research/{id}',['uses'=>'prof\ProfForeignResearchController@delete']);


		//外籍學者蒞校訪問

	Route::get('/foreign_prof_vist',['uses'=>'prof\ForeignProfVistController@index']);
	Route::post('/foreign_prof_vist',['uses'=>'prof\ForeignProfVistController@insert']);
	Route::get('/foreign_prof_vist/search',['uses' => 'prof\ForeignProfVistController@search']);
	Route::get('/foreign_prof_vist/{id}',['uses'=>'prof\ForeignProfVistController@edit']);
	Route::patch('/foreign_prof_vist/{id}',['uses'=>'prof\ForeignProfVistController@update']);
	Route::delete('/foreign_prof_vist/{id}',['uses'=>'prof\ForeignProfVistController@delete']);



	//學生專區

		//赴國外出席國際會議

	Route::get('/stu_attend_conf',['uses'=>'stu\StuAttendConfController@index']);
	Route::post('/stu_attend_conf',['uses'=>'stu\StuAttendConfController@insert']);

		//出國赴姊妹校交換計畫

	Route::get('/stu_to_partner_school',['uses'=>'stu\StuToPartnerSchoolController@index']);
	Route::post('/stu_to_partner_school',['uses'=>'stu\StuToPartnerSchoolController@insert']);

		//其他出國研修情形

	Route::get('/stu_foreign_research',['uses'=>'stu\StuForeignResearchController@index']);
	Route::post('/stu_foreign_research',['uses'=>'stu\StuForeignResearchController@insert']);

		//姊妹校學生至本校參加交換計畫

	Route::get('/stu_from_partner_school',['uses'=>'stu\StuFromPartnerSchoolController@index']);
	Route::post('/stu_from_partner_school',['uses'=>'stu\StuFromPartnerSchoolController@insert']);

	/*Route::get('/stu_from_partner_school',function(){
		$data=['name'=>'asd'];
		return view('stu/stu_from_partner_school',$data);
	});*/

		//外籍學生至本校短期交流訪問

	Route::get('/short_term_foreign_stu',['uses'=>'stu\ShortTermForeignStuController@index']);
	Route::post('/short_term_foreign_stu',['uses'=>'stu\ShortTermForeignStuController@insert']);

		//修讀正式學位之外國學生

	Route::get('/foreign_stu',['uses'=>'stu\ForeignStuController@index']);
	Route::post('/foreign_stu',['uses'=>'stu\ForeignStuController@insert']);

	//其他國際交流活動
	
		//跨國學位

	Route::get('/transnational_degree',['uses'=>'other\TransnationalDegreeController@index']);
	Route::post('/transnational_degree',['uses'=>'other\TransnationalDegreeController@insert']);

		//姊妹校締約情形

	Route::get('/partner_school',['uses'=>'other\PartnerSchoolController@index']);
	Route::post('/partner_school',['uses'=>'other\PartnerSchoolController@insert']);

		//國際合作交流計畫

	Route::get('/cooperation_proj',['uses'=>'other\CooperationProjController@index']);
	Route::post('/cooperation_proj',['uses'=>'other\CooperationProjController@insert']);

		//國際化活動
	
	Route::get('internationalize_activity',['uses'=>'other\InternationalizeActivityController@index']);
	Route::post('internationalize_activity',['uses'=>'other\InternationalizeActivityController@insert']);
	
	

});
