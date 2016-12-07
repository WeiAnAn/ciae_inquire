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
Route::get('/login', 'Auth\LoginController@showLoginForm');
Route::post('/login','Auth\LoginController@login');
Route::post('/logout','Auth\LoginController@logout');


Route::group(['middleware' => 'auth'],function(){
	Route::get('/home','HomeController@index');

		//單位資料修改

	Route::get('/user','UserController@index');
	Route::patch('/user','UserController@update');

	Route::get('/manage','UserController@manage');
	Route::get('/manage/search','UserController@search');
	Route::get('/manage/{id}','UserController@edit');
	Route::post('/manage','UserController@insert');
	Route::delete('/manage/{id}','UserController@delete');

		//英檢畢業門檻
	
	Route::get('/graduate_threshold','user\GraduateThresholdController@index');
	Route::get('/graduate_threshold/search','user\GraduateThresholdController@search');
	Route::get('/graduate_threshold/example','user\GraduateThresholdController@example');	
	Route::get('/graduate_threshold/{id}','user\GraduateThresholdController@edit');
	Route::post('/graduate_threshold','user\GraduateThresholdController@insert');
	Route::post('/graduate_threshold/upload','user\GraduateThresholdController@upload');
	Route::patch('/graduate_threshold/{id}','user\GraduateThresholdController@update');
	Route::delete('/graduate_threshold/{id}','user\GraduateThresholdController@delete');

		//全外語授課課程

	Route::get('/foreign_language_class','user\ForeignLanguageClassController@index');
	Route::get('/foreign_language_class/search','user\ForeignLanguageClassController@search');
	Route::get('/foreign_language_class/example','user\ForeignLanguageClassController@example');
	Route::get('/foreign_language_class/{id}','user\ForeignLanguageClassController@edit');
	Route::post('/foreign_language_class','user\ForeignLanguageClassController@insert');
	Route::post('/foreign_language_class/upload','user\ForeignLanguageClassController@upload');
	Route::patch('/foreign_language_class/{id}','user\ForeignLanguageClassController@update');
	Route::delete('/foreign_language_class/{id}','user\ForeignLanguageClassController@delete');


	//教師、研究員專區
	
		//本校教師赴國外出席國際會議

	Route::get('/prof_attend_conference','prof\ProfAttendConferenceController@index');
	Route::get('/prof_attend_conference/search','prof\ProfAttendConferenceController@search');
	Route::get('/prof_attend_conference/example','prof\ProfAttendConferenceController@example');
	Route::get('/prof_attend_conference/{id}','prof\ProfAttendConferenceController@edit');
	Route::post('/prof_attend_conference','prof\ProfAttendConferenceController@insert');	
	Route::post('/prof_attend_conference/upload','prof\ProfAttendConferenceController@upload');
	Route::patch('/prof_attend_conference/{id}','prof\ProfAttendConferenceController@update');
	Route::delete('/prof_attend_conference/{id}','prof\ProfAttendConferenceController@delete');

	
		//本校教師赴國外交換

	Route::get('/prof_exchange','prof\ProfExchangeController@index');
	Route::get('/prof_exchange/search','prof\ProfExchangeController@search');
	Route::get('/prof_exchange/example','prof\ProfExchangeController@example');
	Route::get('/prof_exchange/{id}','prof\ProfExchangeController@edit');
	Route::post('/prof_exchange','prof\ProfExchangeController@insert');
	Route::post('/prof_exchange/upload','prof\ProfExchangeController@upload');
	Route::patch('/prof_exchange/{id}','prof\ProfExchangeController@update');
	Route::delete('/prof_exchange/{id}','prof\ProfExchangeController@delete');


		//本校教師赴國外交換

	Route::get('/prof_foreign_research','prof\ProfForeignResearchController@index');
	Route::get('/prof_foreign_research/search','prof\ProfForeignResearchController@search');
	Route::get('/prof_foreign_research/example','prof\ProfForeignResearchController@example');
	Route::get('/prof_foreign_research/{id}','prof\ProfForeignResearchController@edit');
	Route::post('/prof_foreign_research','prof\ProfForeignResearchController@insert');
	Route::post('/prof_foreign_research/upload','prof\ProfForeignResearchController@upload');
	Route::patch('/prof_foreign_research/{id}','prof\ProfForeignResearchController@update');
	Route::delete('/prof_foreign_research/{id}','prof\ProfForeignResearchController@delete');


		//外籍學者蒞校訪問

	Route::get('/foreign_prof_vist','prof\ForeignProfVistController@index');
	Route::get('/foreign_prof_vist/search','prof\ForeignProfVistController@search');
	Route::get('/foreign_prof_vist/example','prof\ForeignProfVistController@example');
	Route::get('/foreign_prof_vist/{id}','prof\ForeignProfVistController@edit');
	Route::post('/foreign_prof_vist','prof\ForeignProfVistController@insert');
	Route::post('/foreign_prof_vist/upload','prof\ForeignProfVistController@upload');
	Route::patch('/foreign_prof_vist/{id}','prof\ForeignProfVistController@update');
	Route::delete('/foreign_prof_vist/{id}','prof\ForeignProfVistController@delete');



	//學生專區

		//赴國外出席國際會議

	Route::get('/stu_attend_conf','stu\StuAttendConfController@index');
	Route::get('/stu_attend_conf/search','stu\StuAttendConfController@search');
	Route::get('/stu_attend_conf/example','stu\StuAttendConfController@example');
	Route::get('/stu_attend_conf/{id}','stu\StuAttendConfController@edit');
	Route::post('/stu_attend_conf','stu\StuAttendConfController@insert');
	Route::post('/stu_attend_conf/upload','stu\StuAttendConfController@upload');
	Route::patch('/stu_attend_conf/{id}','stu\StuAttendConfController@update');
	Route::delete('/stu_attend_conf/{id}','stu\StuAttendConfController@delete');

		//出國赴姊妹校交換計畫

	Route::get('/stu_to_partner_school','stu\StuToPartnerSchoolController@index');
	Route::get('/stu_to_partner_school/search','stu\StuToPartnerSchoolController@search');
	Route::get('/stu_to_partner_school/example','stu\StuToPartnerSchoolController@example');
	Route::get('/stu_to_partner_school/{id}','stu\StuToPartnerSchoolController@edit');
	Route::post('/stu_to_partner_school','stu\StuToPartnerSchoolController@insert');
	Route::post('/stu_to_partner_school/upload','stu\StuToPartnerSchoolController@upload');
	Route::patch('/stu_to_partner_school/{id}','stu\StuToPartnerSchoolController@update');
	Route::delete('/stu_to_partner_school/{id}','stu\StuToPartnerSchoolController@delete');

		//其他出國研修情形

	Route::get('/stu_foreign_research','stu\StuForeignResearchController@index');
	Route::get('/stu_foreign_research/search','stu\StuForeignResearchController@search');
	Route::get('/stu_foreign_research/example','stu\StuForeignResearchController@example');
	Route::get('/stu_foreign_research/{id}','stu\StuForeignResearchController@edit');
	Route::post('/stu_foreign_research','stu\StuForeignResearchController@insert');
	Route::post('/stu_foreign_research/upload','stu\StuForeignResearchController@upload');
	Route::patch('/stu_foreign_research/{id}','stu\StuForeignResearchController@update');
	Route::delete('/stu_foreign_research/{id}','stu\StuForeignResearchController@delete');

		//姊妹校學生至本校參加交換計畫

	Route::get('/stu_from_partner_school','stu\StuFromPartnerSchoolController@index');
	Route::get('/stu_from_partner_school/search','stu\StuFromPartnerSchoolController@search');
	Route::get('/stu_from_partner_school/{id}','stu\StuFromPartnerSchoolController@edit');
	Route::get('/stu_from_partner_school/example','stu\StuFromPartnerSchoolController@example');
	Route::post('/stu_from_partner_school','stu\StuFromPartnerSchoolController@insert');
	Route::post('/stu_from_partner_school/upload','stu\StuFromPartnerSchoolController@upload');
	Route::patch('/stu_from_partner_school/{id}','stu\StuFromPartnerSchoolController@update');
	Route::delete('/stu_from_partner_school/{id}','stu\StuFromPartnerSchoolController@delete');
	
		//外籍學生至本校短期交流訪問

	Route::get('/short_term_foreign_stu','stu\ShortTermForeignStuController@index');
	Route::get('/short_term_foreign_stu/search','stu\ShortTermForeignStuController@search');
	Route::get('/short_term_foreign_stu/example','stu\ShortTermForeignStuController@example');
	Route::get('/short_term_foreign_stu/{id}','stu\ShortTermForeignStuController@edit');
	Route::post('/short_term_foreign_stu','stu\ShortTermForeignStuController@insert');
	Route::post('/short_term_foreign_stu/upload','stu\ShortTermForeignStuController@upload');
	Route::patch('/short_term_foreign_stu/{id}','stu\ShortTermForeignStuController@update');
	Route::delete('/short_term_foreign_stu/{id}','stu\ShortTermForeignStuController@delete');

		//修讀正式學位之外國學生

	Route::get('/foreign_stu','stu\ForeignStuController@index');
	Route::get('/foreign_stu/search','stu\ForeignStuController@search');
	Route::get('/foreign_stu/example','stu\ForeignStuController@example');
	Route::get('/foreign_stu/{id}','stu\ForeignStuController@edit');
	Route::post('/foreign_stu','stu\ForeignStuController@insert');
	Route::post('/foreign_stu/upload','stu\ForeignStuController@upload');
	Route::patch('/foreign_stu/{id}','stu\ForeignStuController@update');
	Route::delete('/foreign_stu/{id}','stu\ForeignStuController@delete');

	//其他國際交流活動
	
		//跨國學位

	Route::get('/transnational_degree','other\TransnationalDegreeController@index');
	Route::get('/transnational_degree/search','other\TransnationalDegreeController@search');
	Route::get('/transnational_degree/example','other\TransnationalDegreeController@example');
	Route::get('/transnational_degree/{id}','other\TransnationalDegreeController@edit');
	Route::post('/transnational_degree','other\TransnationalDegreeController@insert');
	Route::post('/transnational_degree/upload','other\TransnationalDegreeController@upload');
	Route::patch('/transnational_degree/{id}','other\TransnationalDegreeController@update');
	Route::delete('/transnational_degree/{id}','other\TransnationalDegreeController@delete');

		//姊妹校締約情形

	Route::get('/partner_school','other\PartnerSchoolController@index');
	Route::get('/partner_school/search','other\PartnerSchoolController@search');
	Route::get('/partner_school/example','other\PartnerSchoolController@example');
	Route::get('/partner_school/{id}','other\PartnerSchoolController@edit');
	Route::post('/partner_school','other\PartnerSchoolController@insert');
	Route::post('/partner_school/upload','other\PartnerSchoolController@upload');
	Route::patch('/partner_school/{id}','other\PartnerSchoolController@update');
	Route::delete('/partner_school/{id}','other\PartnerSchoolController@delete');

		//國際合作交流計畫

	Route::get('/cooperation_proj','other\CooperationProjController@index');
	Route::get('/cooperation_proj/search','other\CooperationProjController@search');
	Route::get('/cooperation_proj/example','other\CooperationProjController@example');
	Route::get('/cooperation_proj/{id}','other\CooperationProjController@edit');
	Route::post('/cooperation_proj','other\CooperationProjController@insert');
	Route::post('/cooperation_proj/upload','other\CooperationProjController@upload');
	Route::patch('/cooperation_proj/{id}','other\CooperationProjController@update');
	Route::delete('/cooperation_proj/{id}','other\CooperationProjController@delete');

		//國際化活動
	
	Route::get('internationalize_activity','other\InternationalizeActivityController@index');
	Route::get('/internationalize_activity/search',
		'other\InternationalizeActivityController@search');
	Route::get('/internationalize_activity/example',
		'other\InternationalizeActivityController@example');
	Route::get('/internationalize_activity/{id}','other\InternationalizeActivityController@edit');
	Route::post('internationalize_activity','other\InternationalizeActivityController@insert');
	Route::post('/internationalize_activity/upload',
		'other\InternationalizeActivityController@upload');
	Route::patch('/internationalize_activity/{id}',
		'other\InternationalizeActivityController@update');
	Route::delete('/internationalize_activity/{id}',
		'other\InternationalizeActivityController@delete');
	
		//系所對照表下載

	Route::get('/example',function(){
        return response()->download(public_path().'/Excel_example/college_data.xlsx',"系所對照表.xlsx");
	});
	

});
