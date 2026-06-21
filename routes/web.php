<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\MainCategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\SubInCategoryController;
use App\Http\Controllers\Admin\CategoryImagesController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\SubFaqController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReviewsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CartItemController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderItemController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProductShippingController;
use App\Http\Controllers\Admin\SaveCardController;
use App\Http\Controllers\Admin\ReturnOrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ProductServiceController;
use App\Http\Controllers\Admin\ReturnExchangePolicyController;
use App\Http\Controllers\Admin\TermConditionController;
use App\Http\Controllers\Admin\UserAddressController;
use App\Http\Controllers\Admin\WishListController;
use App\Http\Controllers\Admin\DeleteAccountController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\DemoController;

/*
|--------------------------------------------------------------------------
| Root
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Locale Switch
|--------------------------------------------------------------------------
*/
Route::match(['get', 'post'], '/locale', function (\Illuminate\Http\Request $request) {
    $supported = ['en', 'hi', 'gu'];
    $locale    = $request->input('locale', 'en');
    if (in_array($locale, $supported)) {
        session(['locale' => $locale]);
    }
    return back();
})->name('locale.switch')->middleware('web');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Category
    Route::resource('category', MainCategoryController::class)->names('category');
    Route::resource('subcategory', SubCategoryController::class)->names('subcategory');
    Route::resource('subincategory', SubInCategoryController::class)->names('subincategory');
    Route::resource('categoryimage', CategoryImagesController::class)->names('categoryimage');

    // Product Attributes
    Route::get('product/{product}/attributes', [ProductAttributeController::class, 'edit'])->name('product.attributes.edit');
    Route::post('product/{product}/attributes', [ProductAttributeController::class, 'update'])->name('product.attributes.update');

    // Subcategories by category (AJAX)
    Route::get('subcategories-by-category/{category_id}', function ($category_id) {
        $subs = \App\Models\SubCategory::where('category_id', $category_id)->where('status', 'active')->get(['id', 'name']);
        return response()->json($subs);
    })->name('subcategories.by.category');

    // Sub In Categories by subcategory (AJAX)
    Route::get('subincategories-by-subcategory/{subcategory_id}', function ($subcategory_id) {
        $subs = \App\Models\SubInCategory::where('subcategory_id', $subcategory_id)->where('status', 'active')->get(['id', 'name']);
        return response()->json($subs);
    })->name('subincategories.by.subcategory');

    // Global status toggle
    Route::post('status/toggle', function (\Illuminate\Http\Request $request) {
        $model = '\\App\\Models\\' . $request->model;
        $record = $model::findOrFail($request->id);
        $record->status = $record->status === 'active' ? 'inactive' : 'active';
        $record->save();
        return response()->json(['status' => $record->status]);
    })->name('status.toggle');
    // product routes
    Route::post('product/delete-image', [ProductController::class, 'deleteImage'])->name('product.deleteImage');
    Route::resource('product', ProductController::class)->names('product');


    Route::resource('banner', BannerController::class)->names('banner');
    Route::resource('brand', BrandController::class)->names('brand');
    Route::resource('contactus', ContactUsController::class)->names('contactus');
    Route::resource('coupon', CouponController::class)->names('coupon');
    Route::resource('faq', FaqController::class)->names('faq');
    Route::resource('sub-faq', SubFaqController::class)->names('sub-faq');
    Route::resource('offer', OfferController::class)->names('offer');
    Route::resource('user', UserController::class)->names('user');
    Route::resource('reviews', ReviewsController::class)->names('reviews');
    Route::resource('term_condition', TermConditionController::class)->names('term_condition');
    Route::resource('role', RoleController::class)->names('role');
    Route::resource('cart-item', CartItemController::class)->names('cart-item');
    Route::resource('wishlist', WishListController::class)->names('wishlist');
    Route::resource('order', OrderController::class)->names('order');
    Route::resource('order-item', OrderItemController::class)->names('order-item');
    Route::resource('user-address', UserAddressController::class)->names('user-address');
    Route::resource('stock', StockController::class)->names('stock');
    Route::resource('return-exchange-policy', ReturnExchangePolicyController::class)->names('return-exchange-policy');
    Route::resource('payment', PaymentController::class)->names('payment');
    Route::resource('blog-category', BlogCategoryController::class)->names('blog-category');
    Route::resource('blog', BlogController::class)->names('blog');
    Route::resource('return-order', ReturnOrderController::class)->names('return-order');
    Route::resource('product-service', ProductServiceController::class)->names('product-service');
    Route::resource('product-shipping', ProductShippingController::class)->names('product-shipping');
    Route::resource('save-card', SaveCardController::class)->names('save-card');
    Route::resource('delete-account', DeleteAccountController::class)->names('delete-account');
    Route::resource('notification', NotificationController::class)->names('notification');
    Route::resource('demo', DemoController::class)->names('demo');
});

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// });

Route::middleware('auth')->group(function () {
    Route::get('/profile',            [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile',         [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
