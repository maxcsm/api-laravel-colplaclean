<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->group(function() {
    Route::namespace('Api')->group(function() {

      Route::post('/change_password/{id}', 'AuthController@change_password');
      // Route::post('refresh', 'AuthController@refresh');
      // Route::apiResource('admins','AdminsController');
      Route::get('/posts/posts_user/{id_user}', 'PostsController@postsByUser');
      Route::get('/posts/posts_user/{id_user}', 'PostsController@postsByUser');
      Route::get('/posts/posts_user_short/{id_user}', 'PostsController@postsByUserShort');

      Route::get('/userByrole', 'UsersController@userByrole');

      Route::apiResource('products','ProductsController');

      Route::apiResource('comments', 'CommentsController');
      Route::get('/comments/commentsbypost/{id_posts}', 'CommentsController@commentsByPost');
      Route::get('/gallery/gallerybypost/{id_posts}', 'GalleryController@galleryByPost');

      Route::apiResource('pages','PagesController');

      Route::apiResource('threads', 'ThreadsController');
      Route::get('threads_user_id/{id_user}', 'ThreadsController@threadByuser');
      Route::apiResource('messages', 'MessagesController');

      Route::apiResource('surveys', 'SurveysController');
      Route::put('/user_avatar/{id}', 'UsersController@updateAvatar');
      //Route::post('/imageupload','ImageUploadController@imageUploadPost');

      Route::post('sendrdv', 'EmailsController@sendrdv');
      Route::post('sendform1', 'EmailsController@sendform1');
      Route::get('/stat', 'StatController@posts_count');


      Route::apiResource('notifications','NotificationsController');
      Route::get('/notifications/notifications_user/{id_user}', 'NotificationsController@notificationsByUser');
      Route::get('/notifications/notifications_user_count/{id_user}', 'NotificationsController@notificationsByUserCount');

     
      Route::post('/addnotif','NotificationsController@addnotif');
      Route::post('/addnotifdefault','NotificationsController@addnotifdefault');
     
       //Route::apiResource('gallery','GalleryController');
       //Route::apiResource('notifs','NotifsController');
       //Route::get('/notifs/notifs_user/{id_user}', 'NotifsController@postsByUser');
       Route::get('/sendmail', 'MailController@sendmail');
       Route::get('/sitemap-posts.xml', 'SitemapController@posts');
 
       Route::post('savepdfwithoutemail', 'EmailsController@savepdfwithoutemail');
    
       Route::get('/public_count', 'PostsController@public_count');
       Route::get('/projetscount', 'ProjectsController@postscount');   
     
       Route::get('/timerbyid/{id}', 'TimerController@timerByUser');
       Route::apiResource('timers','TimerController');
       
       Route::apiResource('tasks','TaskController');
       Route::get('/task_byidinvoice/{id}', 'TaskController@postsByInvoice');

       Route::post('/stoptaskupdate/{id}', 'TimerController@stopTask');
       Route::post('/addcommentaire', 'TimerController@addcommentaire');
       Route::get('/historybyinvoice/{id}', 'TimerController@historyByInvoice');
       Route::get('/timerbyuserbydate/{id}', 'TimerController@TimerByUserBydate');
       Route::post('/timerstats', 'TimerController@TimerStats');
       
       Route::apiResource('questions','QuestionsController');
       Route::get('/questions/questions_survey/{id_survey}', 'QuestionsController@questionsBySurvey');
 



     });
});

Route::namespace('Api')->group(function() {




       Route::apiResource('appointements','AppointementsController');
       Route::get('/appointements/appointements_user/{id_user}', 'AppointementsController@appointementsByUser');
       Route::get('/appointements/appointements_usershort/{id_user}', 'AppointementsController@appointementsByUserShort');
       Route::get('/appointementByUser/{id_user}', 'AppointementsController@appointementByUser');
       Route::post('/getlocation', 'AppointementsController@getlocation');
       Route::post('/saveappointement', 'AppointementsController@saveappointement');
       Route::get('/locationsmap', 'AppointementsController@getLocationAppointement');

       Route::get('/gallerieBypost/{id}', 'AppointementsController@gallerieBypost');
        

       Route::post('saveformpdf1', 'ProjectsController@saveformpdf1');
       Route::post('saveformpdf2', 'ProjectsController@saveformpdf2');
       Route::post('saveformpdf3', 'ProjectsController@saveformpdf3');
       Route::apiResource('projects','ProjectsController');

       Route::get('/projects_byuser/{id}', 'ProjectsController@allprojects');

       
       Route::apiResource('users','UsersController');
       //Route::apiResource('users','UsersController');
       Route::post('/public_location', 'LocationController@public_location');
       Route::post('/public_location_map', 'LocationController@public_location_map');
       Route::get('/public_location_detail/{id}', 'LocationController@public_location_detail');
       Route::get('/public_posts_short', 'PostsController@public_posts_short');
       Route::get('/public_locations_short', 'LocationController@public_locations_short');

       Route::get('/public_post/{id}', 'PostsController@public_post');
       Route::get('/public_tags', 'TagsController@public_tags');
       Route::get('/tags_bylocation/{id}', 'LocationController@tags_bylocation');
       Route::apiResource('locations', 'LocationController');
       Route::apiResource('tagslocations', 'TagslocationController');
     
       Route::apiResource('favoris', 'FavorisController');
       Route::get('/checkfavoris', 'FavorisController@checkfavoris');

        Route::post('/login', 'AuthController@login');
        Route::post('/register','AuthController@register');
        Route::post('/verifywithcode', 'VerificationApiController@verifywithcode');
        // Route::post('logout', 'AuthController@logout');
        Route::post('adduser', 'AuthController@adduser');

        Route::post('/forgotpassword', 'AuthController@resetpassword');
        Route::apiResource('tags', 'TagsController');

        Route::get('email/verify/{id}','VerificationApiController@verify')->name('verificationapi.verify');
        Route::get('email/resend','VerificationApiController@resend')->name('verificationapi.resend');
        Route::get('/verify/{token}', 'VerificationApiController@VerifyEmail');
       
        Route::post('testemail', 'EmailsController@testemail');

        /////Invoices
        Route::apiResource('invoices','InvoicesController');
        Route::get('/invoicesByUser/{id_user}', 'InvoicesController@invoicesByUser');
        Route::get('invoiceid/{id}', 'InvoicesController@invoiceById');
        Route::get('invoice/{id}', 'InvoicesController@invoiceview');
        Route::post('invoicesend/{id}', 'InvoicesController@invoicesend');
        Route::post('addItemInvoice', 'InvoicesController@addItemInvoice');
        Route::post('updateAllprice', 'InvoicesController@updateAllprice');
        Route::post('updateInvoiceId/{id}', 'InvoicesController@updateInvoiceId');


          /////Quotes
          Route::apiResource('quotes','QuotesController');
          Route::get('/quotesByUser/{id_user}', 'QuotesController@quotesByUser');
          Route::get('quotesid/{id}', 'QuotesController@invoiceById');
          Route::get('quote/{id}', 'QuotesController@invoiceview');
          Route::post('quotesend/{id}', 'QuotesController@quotesend');
          Route::post('addItemQuote', 'QuotesController@addItemInvoice');
          Route::post('updateAllpriceQuote', 'QuotesController@updateAllprice');
          Route::post('updateQuoteId/{id}', 'QuotesController@updateInvoiceId');
  
    
        Route::get('image-gallery', 'ImageGalleryController@index');
        Route::post('upload', 'ImageGalleryController@upload');
        Route::get('getimagepython', 'ImageGalleryController@getimagepython');
        Route::get('getimagepythonpixel', 'ImageGalleryController@getimagepythonpixel2');
        
        Route::post('uploadpost', 'ImageGalleryController@uploadpost');
        Route::delete('image-gallery/{id}', 'ImageGalleryController@destroy');

        /////EMAIL AUTOMATIQUES
        Route::get('/appointementonemonth', 'AppointementsController@AllAppointementOneMonth');
        Route::get('/appointementtowmonth', 'AppointementsController@AllAppointementTowMonth');
        Route::get('/allinvoicesclose', 'InvoicesController@Allinvoicesclose');


        

        Route::get('/clear-cache', function() {
          $exitCode = Artisan::call('cache:clear');
          // return what you want
        });

        });


          