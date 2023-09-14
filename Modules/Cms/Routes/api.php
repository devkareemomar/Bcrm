<?php

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

Route::group(['prefix' => 'v1/cms'], function () {

    // Guarded Routes
    Route::group(['middleware' => 'auth.api'], function () {

        // Categories Crud Routes
        Route::delete('categories', 'Categories\CategoryController@bulkDestroy');
        Route::get('categories/brief', 'Categories\CategoryController@brief');
        Route::resource('categories', 'Categories\CategoryController')->except(['create', 'edit']);

        // Services Crud Routes
        Route::delete('services', 'Services\ServiceController@bulkDestroy');
        Route::resource('services', 'Services\ServiceController')->except(['create', 'edit']);

        // Galleries Crud Routes
        Route::delete('galleries', 'Galleries\GalleryController@bulkDestroy');
        Route::resource('galleries', 'Galleries\GalleryController')->except(['create', 'edit']);

        // Sliders Crud Routes
        Route::delete('sliders', 'Sliders\SliderController@bulkDestroy');
        Route::resource('sliders', 'Sliders\SliderController')->except(['create', 'edit']);

        // Faqs Crud Routes
        Route::delete('faqs', 'Faqs\FaqController@bulkDestroy');
        Route::resource('faqs', 'Faqs\FaqController')->except(['create', 'edit']);

        // Pages Crud Routes
        Route::delete('pages', 'Pages\PageController@bulkDestroy');
        Route::resource('pages', 'Pages\PageController')->except(['create', 'edit']);

        // Solutions Crud Routes
        Route::get('solutions/brief', 'Solutions\SolutionController@brief');
        Route::delete('solutions', 'Solutions\SolutionController@bulkDestroy');
        Route::resource('solutions', 'Solutions\SolutionController')->except(['create', 'edit']);

        // Partners Crud Routes
        Route::delete('partners', 'Partners\PartnerController@bulkDestroy');
        Route::resource('partners', 'Partners\PartnerController')->except(['create', 'edit']);

        // Jobs Crud Routes
        Route::delete('jobs', 'Jobs\JobController@bulkDestroy');
        Route::resource('jobs', 'Jobs\JobController')->except(['create', 'edit']);

        // Posts Crud Routes
        Route::delete('posts', 'Posts\PostController@bulkDestroy');
        Route::resource('posts', 'Posts\PostController')->except(['create', 'edit']);

        // PostsComments Routes
        Route::get('posts/{post_id}/comments', 'Posts\CommentController@index');
        Route::delete('comments', 'Posts\CommentController@bulkDestroy');
        Route::resource('comments', 'Posts\CommentController')->only(['show', 'destroy']);

        // Procuts Crud Routes
        Route::delete('products', 'Products\ProductController@bulkDestroy');
        Route::resource('products', 'Products\ProductController')->except(['create', 'edit']);

        // ProcutsPhoto Crud Routes
        Route::delete('products/{product_id}/photos', 'Products\ProductPhotoController@bulkDestroy');
        Route::resource('products/{product_id}/photos', 'Products\ProductPhotoController')->except(['create', 'edit']);

        // Messages Routes
        Route::delete('messages', 'Messages\MessageController@bulkDestroy');
        Route::resource('messages', 'Messages\MessageController')->only(['index', 'show', 'destroy']);

        // NewsLetters Routes
        Route::delete('news-letters', 'NewsLetters\NewsLetterController@bulkDestroy');
        Route::resource('news-letters', 'NewsLetters\NewsLetterController')->only(['index', 'show', 'destroy']);

        // Media Routes
        Route::delete('media', 'Media\MediaController@bulkDestroy');
        Route::resource('media', 'Media\MediaController')->except(['create', 'edit']);

        // Setting Routes
        Route::get('seo', 'Setting\SeoSettingController@index');
        Route::put('seo', 'Setting\SeoSettingController@update');
    });
});
