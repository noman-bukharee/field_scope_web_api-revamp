<?php /** @noinspection ALL */

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'HomeController@index');
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    // Route::group(['middleware' => ['auth', 'check.role']], function () {
    Route::get('user_type', function () {
        return view('admin.user_type');
    });
    // });
    Route::get('project', function () {
        return view('admin.project');
    })->name('project');
    Route::get('photo_feed', function () {
        return view('admin.photo_feed');
    })->name('photo_feed');
   
    Route::get('inspection_area', function () {
        return view('admin.inspection_area');
    })->name('inspection_area');
    Route::get('photo_views', function () {
        return view('admin.photo_views');
    })->name('photo_views');
    Route::get('required_photos', function () {
        return view('admin.required_photos');
    })->name('required_photos');
    Route::get('photo_tags', function () {
        return view('admin.inspection_photo_tags');
    })->name('photo_tags');
    // Route::get('photo_tags', function () {
    //     return view('admin.photo_tags');
    // });
    // Route::get('inspection_survey', function () {
    //     return view('admin.inspection_survey');
    // });
    
    Route::get('questionnaire', function () {
        return view('admin.questionnaire');
    })->name('questionnaire');
    Route::get('user_management', function () {
        return view('admin.user_management');
    })->name('user_management');
    Route::get('subscription', function () {
        return view('admin.subscription');
    })->name('subscription');
    Route::get('reports', function () {
        return view('admin.reports');
    })->name('reports');

    Route::get('login', function () {
        return view('admin.auth.login');
    });

    Route::get('/', function () {
        return view('web.index');
    });
    // Route::get('forbidden', function () {
    //     return view('admin.forbidden');
    // });
    Route::get('forbidden', function () {
        return view('admin.forbidden');
    })->name('forbidden');
    Route::get('master', function () {
        return view('admin.master');
    });
    
});
/***************** Revamp Routes START *****************/


Route::get('signup/{plan?}', 'HomeController@registerView');
Route::post('register/{plan?}', 'HomeController@register');
Route::get('/admin/login', 'UserController@loginIndex')->middleware('guest');
Route::post('/admin/login', 'UserController@login');
Route::get('/admin/login/forget_password', function () {
    return view('admin.auth.forgot_password');
})->middleware('guest');
Route::post('user/forgot/password', 'UserController@forgotPassword');

/***************** Revamp Routes Admin Panel END *****************/

Route::group(['middleware' => ['login.auth','admin.auth'], 'prefix'=>'admin','as'=>'admin.'], function() {

    Route::get('logout', 'UserController@logout');
    // Only admin can access subscription management
    // Route::group(['middleware' => ['check.role:admin']], function () {
        Route::get('re_subscription', 'SubscriptionController@reSubscriptionView');
        Route::post('re_subscription', 'SubscriptionController@reSubscription');
    // });
    
    Route::group(['middleware' => ['subscription.auth']], function() {
        
        Route::get('change_password', 'UserController@changeCompanyPassword');

        
        /************ USERS TYPES ROUTES LOGIC START ***************/
        
        // Managers can access user type management
        
        Route::group(['middleware' => ['check.role:admin']], function () {
            Route::any('/', 'CompanyGroupController@index')->middleware('check.role:admin')->name('user_type');;
            Route::get('', 'CompanyGroupController@companyGroupDatatable')->middleware('check.role:admin')->name('user_type');
            
            Route::get('user_type', 'CompanyGroupController@companyGroupDatatable')->name('user_type');
            Route::post('user_type/store', 'CompanyGroupController@store');
            Route::post('user_type/delete/{id}', 'CompanyGroupController@delete');
            Route::get('user_type/editFormDetails/{id}', 'CompanyGroupController@edit');
            Route::post('user_type/update/{id}', 'CompanyGroupController@update');
        });
        /************ USERS TYPES ROUTES LOGIC END ***************/

        /************ USERS MANAGEMENT ROUTES LOGIC START ***************/

        // Only admin can manage Users routes
        Route::group(['middleware' => ['check.role:admin']], function () {
            Route::get('user_management', 'UserController@inspectorUserDatatable');
            Route::get('inspect_user_datatable', 'UserController@inspectorUserDatatable');
            Route::post('user_management/store', 'UserController@storeInspector');
            Route::post('user_management/delete/{id}', 'UserController@deleteInspector');
            Route::get('user_management/editFormDetails/{id}', 'UserController@edit');
            Route::post('user_management/update/{id}', 'UserController@updateInspector');
        });
        /************ USERS MANAGEMENT ROUTES LOGIC END ***************/

        /************ INPECTION AREA ROUTES LOGIC START ***************/

        // Only admin can manage Inespection routes
        Route::group(['middleware' => ['check.role:admin']], function () {
            Route::get('inspection_area', 'CategoryController@areaList');
            Route::get('inspect_area_datatable', 'CategoryController@areaDatatable');
            Route::post('inspection_area/store', 'CategoryController@storeArea');
            Route::post('delete/inspection_area/{id}', 'CategoryController@deleteArea');
            Route::get('inspection_area/editAreaDetails/{id}', 'CategoryController@editArea');
            Route::post('inspection_area/update/{id}', 'CategoryController@updateArea');
        });
        /************ INPECT AREA ROUTES LOGIC END ***************/

        /************ PHOTO VIEW ROUTES LOGIC START ***************/

        // Example: restrict photo view routes to only admin and manager
        Route::group(['middleware' => ['check.role:admin,manager']], function () {
            Route::get('photo_views', 'CategoryController@photoViewList');
            Route::get('photo_view_datatable', 'CategoryController@photoViewDatatable');
            Route::post('photo_views/store', 'CategoryController@storePhotoView');
            Route::post('delete/photo_views/{id}', 'CategoryController@deleteArea');
            Route::get('photo_views/editPhotoDetails/{id}', 'CategoryController@editPhotoView');
            Route::post('photo_views/update/{id}', 'CategoryController@updatePhotoView');
            Route::get('photo_views/{parent_id}', 'CategoryController@getPhotoView');
        });
        /************ PHOTO VIEW ROUTES LOGIC END ***************/

        /************ REQUIRED PHOTOS ROUTES LOGIC START ***************/
        
        // Example: restrict required_photos routes to only admin
        Route::group(['middleware' => ['check.role:admin']], function () {
            Route::get('required_photos', 'CategoryController@requirePhotoList');
            Route::get('require_photo_datatable', 'CategoryController@requirePhotoDatatable');
            Route::post('required_photos/store', 'CategoryController@storeRequirePhoto');
            Route::post('delete/required_photos/{id}', 'CategoryController@deleteArea');
            Route::get('required_photos/editRequirePhotoDetails/{id}', 'CategoryController@editRequirePhoto');
            Route::post('required_photos/update/{id}', 'CategoryController@updateRequirePhoto');
        });
        /************ REQUIRED PHOTOS ROUTES LOGIC START ***************/

        /************ PHOTO TAGS ROUTES LOGIC START ***************/

        // Example: restrict photo_tags routes to only admin
        Route::group(['middleware' => ['check.role:admin']], function () {
            Route::get('photo_tags', 'TagController@tagList');
            Route::get('tag_datatable', 'TagController@tagDatatable');
            Route::post('photo_tags/store', 'TagController@store')->name('tagStore');
            Route::post('photo_tags/delete/{id}', 'TagController@deleteTag');
            Route::get('photo_tags/editTagDetails/{id}', 'TagController@editTagDetails');
            Route::post('photo_tags/update/{id}', 'TagController@updateTag');

            Route::get('photo_tags/export/template', 'TagController@tagExport');
            Route::get('photo_tags/import', 'TagController@tagImportView');
            Route::post('photo_tags/import', 'TagController@tagImport');
        });
        /************ PHOTO TAGS ROUTES LOGIC END ***************/

        /************ QUESTIONNAIRE ROUTES LOGIC START ***************/

        // Example: restrict questionnaire Management routes to only admin
        Route::group(['middleware' => ['check.role:admin']], function () {
            Route::get('questionnaire', 'QueryController@queryList');
            Route::get('questionnaire_datatable', 'QueryController@queryDatatable');
            Route::get('questionnaire/add', 'QueryController@editQueryDetails');
            Route::get('questionnaire/editQuestionnaireDetails/{id?}', 'QueryController@editQueryDetails');
            //Route::post('questionnaire/store', 'QueryController@storeQuery');
            Route::post('questionnaire/delete/{id}', 'QueryController@deleteQuery');

            Route::post('questionnaire/update/{id?}', 'QueryController@updateQuery');

            // UI routes
            Route::get('questionnaire/edit_select', 'QueryController@editSelect');
            Route::get('questionnaire/edit_select/edit_questionnaire', 'QueryController@edit_questionnaire'); //Edit
            Route::get('questionnaire/add/select_questionnaire_edit_area', 'QueryController@select_questionnaire_edit_area');
        });
        /************ QUESTIONNAIRE ROUTES LOGIC END ***************/

        /************ PROJECT ROUTES LOGIC START ***************/

        // Example: restrict project visit routes to only admin and manager
        Route::group(['middleware' => ['check.role:admin,manager,standard']], function () {
            Route::get('project', 'ProjectController@projectGrid');
            Route::get('project_datatable', 'ProjectController@projectGrid');
            Route::get('project/detail/{id}', 'ProjectController@detailView');
            Route::get('project/reportView/{id}', 'ProjectController@reportView');
            Route::post('project/report/{id}', 'ProjectController@report');
            Route::post('project/save-signature','ProjectController@saveSignature');
             /**Photos Details */
             Route::get('project/photo_details/{id}', 'MediaController@details');
        });

        // Admin only has write access for project-related routes
        Route::group(['middleware' => ['check.role:admin,manager']], function () {
            Route::get('project/add', 'ProjectController@projectCreate');
            Route::post('project/store', 'ProjectController@storeProject');
            Route::post('project/delete/{id}', 'ProjectController@deleteProject');
            Route::get('project/editProjectDetails/{id}', 'ProjectController@editProjectDetails');
            Route::post('project/update/{id}', 'ProjectController@updateProject');
            Route::get('project/edit-project/{id}', 'ProjectController@detailView');
            Route::get('project/photo/details/{id}', 'MediaController@project_photo_details');
            Route::get('project/photo/edit/{id}', 'MediaController@project_photo_edit');

            /************ PROJECT ROUTES LOGIC END ***************/

        });
        Route::get('sample', 'ProjectController@sampleReport');


        Route::get('cities/{id}', 'GeneralController@getAjaxCities');

        /************ SUBSCRIPTION ROUTES LOGIC START ***************/

        // Only admin can manage subscription routes
        Route::group(['middleware' => ['check.role:admin']], function () {
            Route::get('subscription', 'SubscriptionController@subsList');
            Route::get('subscription/editSubsDetails/{id}', 'SubscriptionController@editSubsDetails');
            Route::post('subscription/update', 'SubscriptionController@updateSubs');
        });
        /************ SUBSCRIPTION ROUTES LOGIC END ***************/

        // Other reports and settings access based on roles
        Route::group(['middleware' => ['check.role:admin']], function () {
            Route::get('analytics', 'HomeController@dashboard');
            Route::get('settings', 'UserController@settings');
            Route::post('settingsUpdate', 'UserController@settingsUpdate');
            Route::post('delete/{module}/{id}', 'Controller@__delete');
            Route::get('crm/spec_list/{id}', 'CrmProjectController@getSpecList');
            Route::get('crm/sync', 'CrmProjectController@syncProject');
        });
        /************ PHOTO FEEDS ROUTES LOGIC START ***************/

        
        // Other reports and settings access based on roles
        Route::group(['middleware' => ['check.role:admin,standard,manager']], function () {
            Route::get('photo_feed', 'MediaController@listView');
            Route::get('photo_feed_datatable', 'ProjectController@projectDatatable');
        });
            // Other reports and settings access based on roles
        Route::group(['middleware' => ['check.role:admin,manager,standard']], function () {
            Route::get('photo_feed/edit/{id}', 'MediaController@editPhoto');
            Route::post('photo_feed/update/{id}', 'MediaController@updatePhoto');
            Route::get('photo_feed/details/{id}', 'MediaController@details');
        });
        
        /************ PHOTO FEEDS ROUTES LOGIC END ***************/

         /** Eagle View */
        Route::group(['prefix'=>'ev','as'=>'ev.'], function (){
            Route::get('field_list/{id}', 'EagleViewController@fieldList');
            Route::post('auth_user', 'EagleViewController@authorizeUser');
            Route::post('company_product', 'EagleViewController@companyProducts');
        });

        /************ HOVER ROUTES LOGIC START ***************/

        /** Hover */
        Route::group(['prefix'=>'hover','as'=>'hover.'], function (){
            Route::get('field_list/{id}', 'HoverController@fieldList');

            Route::get('get_redirect_url', 'HoverController@getRedirectUri');
            Route::post('set_details', 'HoverController@setHoverDetails');
            Route::get('create_job', 'HoverController@createJob');
            Route::get('parse_job', 'HoverController@parseJob');
        });

        /************ HOVER ROUTES LOGIC END ***************/

        /************ REPORT ROUTES LOGIC START ***************/

        Route::get('report/create/thirdpage/{id}', function (){
            return view("reports.web.report_third_page");
            });
        Route::get('report/create/secondpage/{id}', function (){
            return view("reports.web.report_second_page");
            });
        Route::get('report/create/fourthpage/{id}', function (){
            return view("reports.web.report_fourth_page");
            });
        Route::get('report/create/fifthpage/{id}', function (){
            return view("reports.web.report_fifth_page");
            });
        Route::get('report/create/sixthpage/{id}', function (){
            return view("reports.web.report_sixth_page");
            });
        Route::get('report/create/seventhpage/{id}', function (){
            return view("reports.web.report_seventh_page");
            });
        Route::get('report/create/eighthpage/{id}', function (){
            return view("reports.web.report_eighth_page");
            });
        Route::get('report/create/ninthpage/{id}', function (){
            return view("reports.web.report_ninth_page");
            });
        Route::get('report/create/coverpage/{id}', function (){
            return view("reports.web.report_cover_page");
            });

        /************ REPORT ROUTES LOGIC END ***************/
        
        // Specific report-related routes for admin only
        Route::group(['middleware' => ['check.role:admin']], function () {
            Route::post('report/company-color','ReportController@companyColor');
            Route::post('report/ctc','ReportController@companyTermsConditions');
            Route::post('report/introduction','ReportController@reportIntroduction');
            Route::post('report/save-document','ReportController@saveDocument');
            Route::post('report/document/delete','ReportController@deleteDocument');  
            Route::post('report/owner-authorization','ReportController@storeOwnerAuthorization');
        });
        /************ REPORT MANAGEMENT LOGIC START ***************/

        // Report management for admin only
        Route::group(['prefix' => 'reports', 'as' => 'reports.', 'middleware' => ['check.role:admin']], function () {
            Route::get('/', 'ReportController@listView');
            Route::post('storeLogo', 'ReportController@storeLogo');
            Route::post('storeInfo', 'ReportController@storeInfo'); /** This stores multiple different attributes using $model->fill() */
            Route::post('storeCoverInfo', 'ReportController@storeCoverInfo');

            Route::post('storeIntroduction', 'ReportController@storeIntroduction');
            Route::get('getIntroduction', 'ReportController@getIntroduction');
            Route::post('deleteIntroduction', 'ReportController@deleteIntroduction');
        });

        /************ REPORT MANAGEMENT LOGIC END ***************/
    });    
});    

/***************** Revamp Login END *****************/


//Route::get('home', function () {
//    return view('web/welcome');
//});

/************************************/
Route::get('/analytics', function () {
    return view('web/analytics');
});
// Route::get('subadmin/user-type', function () {
//     return view('subadmin.user_type_mgmt');
// });

// Route::get('subadmin/inspect_user', function () {
//     return view('subadmin.inspect-user_mgmt');
// });
// Route::get('/subadmin/inspect_area', function () {
//     return view('subadmin.inspect-area_mgmt');
// });
// Route::get('/subadmin/photo_view', function () {
//     return view('subadmin.photo-view_mgmt');
// });

Route::get('/report/view', function () {
    return view('reports.report');
});

//Route::get('/subadmin/req-photo', function () {
//    return view('subadmin.req-photo_mgmt');
//});


// Route::get('/subadmin/project_mgmt', function () {
//     return view('subadmin.project_mgmt');
// });
// Route::get('/subadmin/questionnaire_mgmt', function () {
//     return view('subadmin.questionnaire_mgmt');
// });
// Route::get('subadmin/add_questionnaire', function () {
//     return view('subadmin.add_questionnaire');
// });

//Route::get('/subadmin/project_detail', function () {
//    return view('subadmin.project_detail');
//});


Route::group(['prefix'=>'hover','as'=>'hover.'], function (){
    Route::any('hover_log',  'EagleViewController@hover_log');
});

/************************************/

Route::get('home', 'HomeController@index');

// Route::get('signup/{plan?}', 'HomeController@registerView');
// Route::post('register/{plan?}', 'HomeController@register');

// Route::get('/subadmin/login', 'UserController@loginIndex')->middleware('guest');
Route::get('/user/login', 'UserController@loginIndex');
// Route::post('/subadmin/login', 'UserController@login');


// Route::get('subadmin/login/forget_password', function () {
//     return view('subadmin.login.forget_password');
// })->middleware('guest');

// Route::post('user/forgot/password', 'UserController@forgotPassword');

Route::get('project/create_report/{id}', 'ProjectController@createReport')->middleware('responseTime');
Route::get('report/create/{id}', 'ReportController@createReport');
Route::get('report/web_sample', 'ReportController@webSample');
Route::get('report/web_sample_2', function (){
    return view("reports.web_sample_2");
});

//Route::get('imageSync', 'ProjectController@imageSync');

/*Getting hover auth code*/
Route::get('hover/authentication/{code}', 'HoverController@setAuthCode');
Route::get('report/authentication/{code}', 'HoverController@setAuthCode');
Route::post('hover/test_job', 'HoverController@testJob');

Route::get('project/photos/{token}', 'ProjectShareController@photos');

/** PDF SIGN TEST */
Route::group(['prefix'=>'sign','as'=>'sign.'], function (){
    Route::get('auth', 'ReportController@hello');
    Route::get('now', 'ReportController@signNow');
});


Route::get('report/sign/{token}', 'ReportController@customerSignView');
Route::post('report/sign/{token}', 'ReportController@customerSign');

/*Client Admin*/
Route::group(['middleware' => ['login.auth','admin.auth'], 'prefix'=>'subadmin','as'=>'subadmin.'], function() {


    Route::get('logout', 'UserController@logout');
    Route::get('re_subscription', 'SubscriptionController@reSubscriptionView');
    Route::post('re_subscription', 'SubscriptionController@reSubscription');

    Route::group(['middleware' => ['subscription.auth']], function() {

        Route::get('change_password', 'UserController@changeCompanyPassword');

        Route::any('/', 'CompanyGroupController@index');

        Route::get('user-type', 'CompanyGroupController@index');
        Route::get('user-type_datatable', 'CompanyGroupController@companyGroupDatatable');
        Route::post('user-type/store', 'CompanyGroupController@store');
        Route::post('user-type/delete/{id}', 'CompanyGroupController@delete');
        Route::get('user-type/editFormDetails/{id}', 'CompanyGroupController@edit');
        Route::post('user-type/update/{id}', 'CompanyGroupController@update');
        Route::get('', 'CompanyGroupController@index');

        Route::get('inspect_user', 'UserController@inspectorUserList');
        Route::get('inspect_user_datatable', 'UserController@inspectorUserDatatable');
        Route::post('inspect_user/store', 'UserController@storeInspector');
        Route::post('inspect_user/delete/{id}', 'UserController@deleteInspector');
        Route::get('inspect_user/editFormDetails/{id}', 'UserController@edit');
        Route::post('inspect_user/update/{id}', 'UserController@updateInspector');

        /** inspect area*/
        Route::get('inspect_area', 'CategoryController@areaList');
        Route::get('inspect_area_datatable', 'CategoryController@areaDatatable');
        Route::post('inspect_area/store', 'CategoryController@storeArea');
        Route::post('delete/inspect_area/{id}', 'CategoryController@deleteArea');
        Route::get('inspect_area/editAreaDetails/{id}', 'CategoryController@editArea');
        Route::post('inspect_area/update/{id}', 'CategoryController@updateArea');

        /** photo view */
        Route::get('photo_view', 'CategoryController@photoViewList');
        Route::get('photo_view_datatable', 'CategoryController@photoViewDatatable');
        Route::post('photo_view/store', 'CategoryController@storePhotoView');
        Route::post('delete/photo_view/{id}', 'CategoryController@deleteArea');
        Route::get('photo_view/editPhotoDetails/{id}', 'CategoryController@editPhotoView');
        Route::post('photo_view/update/{id}', 'CategoryController@updatePhotoView');
        Route::get('photo_view/{parent_id}', 'CategoryController@getPhotoView');

        /** Required Photos */
        Route::get('require_photo', 'CategoryController@requirePhotoList');
        Route::get('require_photo_datatable', 'CategoryController@requirePhotoDatatable');
        Route::post('require_photo/store', 'CategoryController@storeRequirePhoto');
        Route::post('delete/require_photo/{id}', 'CategoryController@deleteArea');
        Route::get('require_photo/editRequirePhotoDetails/{id}', 'CategoryController@editRequirePhoto');
        Route::post('require_photo/update/{id}', 'CategoryController@updateRequirePhoto');


        /** Req. Tags Disabled at 01-May-2024 */
        /*Route::get('cat_tag', 'TagController@catTagList')->name('catTagList');
        Route::get('cat_tag_datatable', 'TagController@catTagDatatable')->name('catTagList');
        Route::post('cat_tag/store', 'TagController@store')->name('catTagStore');
        Route::post('cat_tag/delete/{id}', 'TagController@deleteTag');
//    Route::get('tag/editTagDetails/{id}', 'TagController@editTagDetails');
        Route::post('cat_tag/update/{id}', 'TagController@updateTag');


        Route::get('cat_tag/export/template', 'TagController@catTagExport');
        Route::get('cat_tag/import', 'TagController@catTagImportView');
        Route::post('cat_tag/import', 'TagController@catTagImport');*/

        /** Photo Tag*/
        Route::get('tag', 'TagController@tagList');
        Route::get('tag_datatable', 'TagController@tagDatatable');
        Route::post('tag/store', 'TagController@store')->name('tagStore');
        Route::post('tag/delete/{id}', 'TagController@deleteTag');
        Route::get('tag/editTagDetails/{id}', 'TagController@editTagDetails');
        Route::post('tag/update/{id}', 'TagController@updateTag');

        Route::get('tag/export/template', 'TagController@tagExport');
        Route::get('tag/import', 'TagController@tagImportView');
        Route::post('tag/import', 'TagController@tagImport');


        Route::get('questionnaire', 'QueryController@queryList');
        Route::get('questionnaire_datatable', 'QueryController@queryDatatable');
        Route::get('questionnaire/add', 'QueryController@editQueryDetails');
        Route::get('questionnaire/editQuestionnaireDetails/{id?}', 'QueryController@editQueryDetails');
        //Route::post('questionnaire/store', 'QueryController@storeQuery');
        Route::post('questionnaire/delete/{id}', 'QueryController@deleteQuery');

        Route::post('questionnaire/update/{id?}', 'QueryController@updateQuery');

        // UI routes
        Route::get('questionnaire/edit_select', 'QueryController@editSelect');
        Route::get('questionnaire/edit_select/edit_questionnaire', 'QueryController@edit_questionnaire'); //Edit
        Route::get('questionnaire/add/select_questionnaire_edit_area', 'QueryController@select_questionnaire_edit_area');



        /** project module*/
        Route::post('project/save-signature','ProjectController@saveSignature');
        Route::get('project', 'ProjectController@projectList');
        Route::get('project_datatable', 'ProjectController@projectGrid');
        Route::get('project/add', 'ProjectController@projectCreate');
        Route::post('project/store', 'ProjectController@storeProject');
        Route::get('project/detail/{id}', 'ProjectController@detailView');
        Route::post('project/delete/{id}', 'ProjectController@deleteProject');
        Route::get('project/editProjectDetails/{id}', 'ProjectController@editProjectDetails');
        Route::post('project/update/{id}', 'ProjectController@updateProject');
        Route::get('project/reportView/{id}', 'ProjectController@reportView');
        Route::post('project/report/{id}', 'ProjectController@report');
        Route::get('project/edit-project/{id}', 'ProjectController@detailView');

        /**Photos Details */
        Route::get('project/photo_details/{id}', 'MediaController@details');

        Route::get('sample', 'ProjectController@sampleReport');


        Route::get('cities/{id}', 'GeneralController@getAjaxCities');

        Route::get('subscription', 'SubscriptionController@subsList');
        /*Route::get('subscription/add', 'SubscriptionController@subsCreate');
        Route::post('subscription/store', 'SubscriptionController@storeSubs');
        Route::get('subscription/delete/{id}', 'SubscriptionController@deleteSubs');*/
        Route::get('subscription/editSubsDetails/{id}', 'SubscriptionController@editSubsDetails');
        Route::post('subscription/update', 'SubscriptionController@updateSubs');

        Route::get('analytics', 'HomeController@dashboard');

        Route::get('settings', 'UserController@settings');
        Route::post('settingsUpdate', 'UserController@settingsUpdate');
        Route::post('delete/{module}/{id}', 'Controller@__delete');
        Route::get('crm/spec_list/{id}', 'CrmProjectController@getSpecList');
        Route::get('crm/sync', 'CrmProjectController@syncProject');

        /** Photo Feed*/
        Route::get('photo_feed', 'MediaController@listView');
        /*Route::get('photo_feed_datatable', 'ProjectController@projectDatatable');*/
        Route::get('photo_feed/edit/{id}', 'MediaController@editPhoto');
        Route::post('photo_feed/update/{id}', 'MediaController@updatePhoto');
        Route::get('photo_feed/details/{id}', 'MediaController@details');

         /** Eagle View */
        Route::group(['prefix'=>'ev','as'=>'ev.'], function (){
            Route::get('field_list/{id}', 'EagleViewController@fieldList');
            Route::post('auth_user', 'EagleViewController@authorizeUser');
            Route::post('company_product', 'EagleViewController@companyProducts');
        });


        /** Hover */
        Route::group(['prefix'=>'hover','as'=>'hover.'], function (){
            Route::get('field_list/{id}', 'HoverController@fieldList');

            Route::get('get_redirect_url', 'HoverController@getRedirectUri');
            Route::post('set_details', 'HoverController@setHoverDetails');
            Route::get('create_job', 'HoverController@createJob');
            Route::get('parse_job', 'HoverController@parseJob');

//            Route::post('auth_user', 'EagleViewController@authorizeUser');
//            Route::post('company_product', 'EagleViewController@companyProducts');
        });

//        Route::get('report/project/{id}', 'ReportController@createReport');





        Route::get('report/create/thirdpage/{id}', function (){
            return view("reports.web.report_third_page");
            });
        Route::get('report/create/secondpage/{id}', function (){
            return view("reports.web.report_second_page");
            });
        Route::get('report/create/fourthpage/{id}', function (){
            return view("reports.web.report_fourth_page");
            });
        Route::get('report/create/fifthpage/{id}', function (){
            return view("reports.web.report_fifth_page");
            });
        Route::get('report/create/sixthpage/{id}', function (){
            return view("reports.web.report_sixth_page");
            });
        Route::get('report/create/seventhpage/{id}', function (){
            return view("reports.web.report_seventh_page");
            });
        Route::get('report/create/eighthpage/{id}', function (){
            return view("reports.web.report_eighth_page");
            });
        Route::get('report/create/ninthpage/{id}', function (){
            return view("reports.web.report_ninth_page");
            });
        Route::get('report/create/coverpage/{id}', function (){
            return view("reports.web.report_cover_page");
            });
        // Route::get('report/report_cover', function (){
        //     return view("reports.web.report_cover");
        // });

        Route::get('feed_list', function () {
            return view('subadmin/feed_list');
        });

        Route::get('view_images', function () {
            return view('subadmin/view_images');
        });
        
        Route::post('report/company-color','ReportController@companyColor');
        Route::post('report/ctc','ReportController@companyTermsConditions');
        Route::post('report/introduction','ReportController@reportIntroduction');
        // Route::post('report/save-document','ReportController@saveDocument');
        // Route::post('report/document/delete','ReportController@deleteDocument');  
        Route::post('report/owner-authorization','ReportController@storeOwnerAuthorization');
        /**Report Management*/
        Route::group(['prefix'=>'report','as'=>'report.'], function () {
            Route::get('/', 'ReportController@listView');
            Route::post('storeLogo', 'ReportController@storeLogo');
            Route::post('storeInfo', 'ReportController@storeInfo'); /** This stores multiple different attributes using $model->fill() */
            Route::post('storeCoverInfo', 'ReportController@storeCoverInfo');

            Route::post('storeIntroduction', 'ReportController@storeIntroduction');
            Route::get('getIntroduction', 'ReportController@getIntroduction');
            Route::post('deleteIntroduction', 'ReportController@deleteIntroduction');
        });
    });
});




/*Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');*/
// Route::post('/admin/auth_login', [UserController::class, 'loginIndex'])->name('login');
Route::get('/layout/about', 'HomeController@layoutAbout');
Route::get('/layout/contact', 'HomeController@layoutSecurity');
Route::post('/admin/subscribe/user', 'UserController@subscribe');
Route::post('/admin/user/donate', 'UserController@addDonation');
Route::any('/user/subscribe', 'UserController@updateSubscription');
Route::any('/user/forgot/password/{token}', 'UserController@changePasswordWeb');
