<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(["verify" => true]);
Route::middleware(['auth', 'verified', 'check.not_admin'])->group(function () {
    //dashboard route
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'show'])->name('dashboard');
    Route::get('/admin', [App\Http\Controllers\Admin\AdminDashboardController::class, 'show'])->name('admin');

    //role route
    Route::get('/admin/role/list', [App\Http\Controllers\Admin\AdminRoleController::class, 'list'])->name('role.list');
    Route::get('/admin/role/add', [App\Http\Controllers\Admin\AdminRoleController::class, 'add'])->name('role.add');

    // user route
    Route::get('/admin/user/list', [App\Http\Controllers\Admin\UserController::class, 'list'])->name('user.list');
    Route::get('/admin/user/add', [App\Http\Controllers\Admin\UserController::class, 'add'])->name('user.add');
    Route::post('/admin/user/store', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('user.store');
    Route::get('/admin/user/multiple-actions', [App\Http\Controllers\Admin\UserController::class, 'multipleActions'])->name('user.multiple_actions');

    Route::get('/admin/user/restore', [App\Http\Controllers\Admin\UserController::class, 'restore'])->name('user.restore');
    Route::get('/admin/user/delete-one/{id}', [App\Http\Controllers\Admin\UserController::class, 'deleteOne'])->name('user.delete_one');
    Route::get('/admin/user/force-delete-one/{id}', [App\Http\Controllers\Admin\UserController::class, 'forceDeleteOne'])->name('user.force_delete_one');
    Route::get('/admin/user/delete-many', [App\Http\Controllers\Admin\UserController::class, 'deleteMany'])->name('user.delete_many');

    Route::get('/admin/user/force-delete-many', [App\Http\Controllers\Admin\UserController::class, 'forceDeleteMany'])->name('user.force_delete_many');
    Route::get('/admin/user/edit/{id}', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('user.edit');
    Route::post('/admin/user/update/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('user.update');

    //product_cat route
    Route::get('/admin/product-cat/list', [App\Http\Controllers\Admin\AdminProductCatController::class, 'list'])->name('product_cat.list');
    Route::post('/admin/product-cat/store', [App\Http\Controllers\Admin\AdminProductCatController::class, 'store'])->name('product_cat.store');
    Route::get('/admin/product-cat/edit/{id}', [App\Http\Controllers\Admin\AdminProductCatController::class, 'edit'])->name('product_cat.edit');
    Route::post('/admin/product-cat/update/{id}', [App\Http\Controllers\Admin\AdminProductCatController::class, 'update'])->name('product_cat.update');

    //upload image
    Route::post('/admin/image/upload', [App\Http\Controllers\Admin\ImageController::class, 'upload'])->name('image.upload');

    //brand route
    Route::get('/admin/brand/list', [App\Http\Controllers\Admin\AdminBrandController::class, 'list'])->name('brand.list');
    Route::post('/admin/brand/store', [App\Http\Controllers\Admin\AdminBrandController::class, 'store'])->name('brand.store');
    Route::get('/admin/brand/edit/{id}', [App\Http\Controllers\Admin\AdminBrandController::class, 'edit'])->name('brand.edit');
    Route::post('/admin/brand/update/{id}', [App\Http\Controllers\Admin\AdminBrandController::class, 'update'])->name('brand.update');

    //product route
    Route::get('/admin/product/list', [App\Http\Controllers\Admin\AdminProductController::class, 'list'])->name('product.list');
    Route::get('/admin/product/add', [App\Http\Controllers\Admin\AdminProductController::class, 'add'])->name('product.add');
    Route::post('/admin/product/store', [App\Http\Controllers\Admin\AdminProductController::class, 'store'])->name('product.store');

    Route::get('/admin/product/edit/{id}', [App\Http\Controllers\Admin\AdminProductController::class, 'edit'])->name('product.edit');
    Route::post('/admin/product/update/{id}', [App\Http\Controllers\Admin\AdminProductController::class, 'update'])->name('product.update');
    Route::get('/admin/product/delete_one/{id}', [App\Http\Controllers\Admin\AdminProductController::class, 'deleteOne'])->name('product.delete_one');
    Route::get('/admin/product/multiple-actions', [App\Http\Controllers\Admin\AdminProductController::class, 'multipleActions'])->name('product.multiple_actions');

    Route::get('/admin/product/delete-many', [App\Http\Controllers\Admin\AdminProductController::class, 'deleteMany'])->name('product.delete_many');
    Route::get('/admin/product/restore', [App\Http\Controllers\Admin\AdminProductController::class, 'restore'])->name('product.restore');
    Route::get('/admin/product/force-delete-many', [App\Http\Controllers\Admin\AdminProductController::class, 'forceDeleteMany'])->name('product.force_delete_many');
    Route::get('/admin/product/force-delete-one/{id}', [App\Http\Controllers\Admin\AdminProductController::class, 'forceDeleteOne'])->name('product.force_delete_one');

    //order route
    Route::get('/admin/order/list', [App\Http\Controllers\Admin\OrderController::class, 'list'])->name('order.list');
    Route::get('/admin/order/detail/{id}', [App\Http\Controllers\Admin\OrderController::class, 'detail'])->name('order.detail');
    Route::get('/admin/order/delete-one/{id}', [App\Http\Controllers\Admin\OrderController::class, 'deleteOne'])->name('order.delete_one');
    Route::get('/admin/order/delete-many', [App\Http\Controllers\Admin\OrderController::class, 'deleteMany'])->name('order.delete_many');

    Route::get('/admin/order/force-delete-one/{id}', [App\Http\Controllers\Admin\OrderController::class, 'forceDeleteOne'])->name('order.force_delete_one');
    Route::get('/admin/order/force-delete-many', [App\Http\Controllers\Admin\OrderController::class, 'forceDeleteMany'])->name('order.force_delete_many');
    Route::get('/admin/order/restore', [App\Http\Controllers\Admin\OrderController::class, 'restore'])->name('order.restore');

    Route::get('/admin/order/edit/{id}', [App\Http\Controllers\Admin\OrderController::class, 'edit'])->name('order.edit');
    Route::post('/admin/order/update/{id}', [App\Http\Controllers\Admin\OrderController::class, 'update'])->name('order.update');
    Route::post('/admin/order/update-status/{id}', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('order.update_status');
    Route::get('/admin/order/multiple-actions', [App\Http\Controllers\Admin\OrderController::class, 'multipleActions'])->name('order.multiple_actions');



});
// Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
//     \UniSharp\LaravelFilemanager\Lfm::routes();
// });


Route::get('/notify/banned', [App\Http\Controllers\NotifyController::class, 'banned'])->name('notify.banned');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
?>