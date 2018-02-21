<?php

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

Route::get('/', 'HomeController@index')->name("index");
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/test', 'InventoryController@test')->name('test');

/* Authentication Routes */
Auth::routes();

/* POS Routes */
Route::get('/pos', 'POSController@show')->name('pos');

/* ================================ AJAX Routes ================================ */
Route::get("/ajax/get-sub-categories/{id}", "CategoryController@getSubCategories")->name("getSubCategories");
Route::get("/ajax/get-product-variations/{id}", "ProductController@getVariations")->name("getProductVariations");
Route::get("/ajax/get-variation-details/{id}", "ProductVariationController@getVariationDetails");
Route::get("/ajax/get-pos-product/{id}", "POSController@getPOSProduct");
Route::get("/ajax/get-pos-product-by-category/{id}", "POSController@getProductsByCategory");
Route::get("/ajax/inventory/summary", "InventoryController@summary");


/* ================================ Product Routes ================================ */
Route::get("/products", "ProductController@show")->name("showProducts");
Route::get("/product/barcode/find", "ProductController@barcodeFind")->name("product.find");
Route::get("/product/create", "ProductController@create")->name("createProduct");
Route::post("/product/create/store", "ProductController@store")->name("storeProduct");
Route::get("/product/edit/{slug}", "ProductController@edit")->name("editProduct");
Route::post("/product/update/{slug}", "ProductController@update")->name("updateProduct");
Route::post("/product/delete/{slug}", "ProductController@delete")->name("deleteProduct");
Route::post("/product/reserve/{slug}", "ProductController@reserve")->name("product.reserve");
Route::post("/product/damaged/{slug}", "ProductController@record_damaged")->name("product.damaged");
Route::post("/product/expired/{slug}", "ProductController@record_expired")->name("product.expired");
Route::post("/product/variation/create", "ProductVariationController@store")->name("storeVariation");
Route::post("/product/variation/update/{slug}", "ProductVariationController@update")->name("updateVariation");


/* ================================ Category Routes ================================ */
Route::get("/inventory/categories", "CategoryController@show")->name("showCategories");
Route::post("/inventory/categories/store", "CategoryController@store")->name("storeCategory");
Route::post("/inventory/categories/edit/{slug}", "CategoryController@edit")->name("editCategory");
Route::post("/inventory/categories/delete/{slug}", "CategoryController@delete")->name("deleteCategory");


/* ================================ SubCategory Routes ================================ */
Route::get("/inventory/subcategories", "SubCategoryController@show")->name("showSubCategories");
Route::post("/inventory/subcategories/store", "SubCategoryController@store")->name("storeSubCategory");
Route::post("/inventory/subcategories/edit/{slug}", "SubCategoryController@edit")->name("editSubCategory");
Route::post("/inventory/subcategories/delete/{slug}", "SubCategoryController@delete")->name("deleteSubCategory");


/* ================================ Brand Routes ================================ */
Route::get("/brands", "BrandController@show")->name("showBrands");
Route::post("/brand/store", "BrandController@store")->name("storeBrand");
Route::post("/brand/edit/{slug}", "BrandController@edit")->name("editBrand");
Route::post("/brand/delete/{slug}", "BrandController@delete")->name("deleteBrand");


/* ================================ Dealers Routes ================================ */
Route::get("/dealers", "DealerController@show")->name("showDealers");
Route::post("/dealer/store", "DealerController@store")->name("storeDealer");
Route::post("/dealer/edit/{slug}", "DealerController@edit")->name("editDealer");
Route::post("/dealer/delete/{slug}", "DealerController@delete")->name("deleteDealer");


/* ================================ Measurement Unit Routes ================================ */
Route::get("/configuration/units", "MeasurementUnitController@show")->name("showUnits");
Route::post("/configuration/unit/store", "MeasurementUnitController@store")->name("storeUnit");
Route::post("/configuration/unit/edit/{id}", "MeasurementUnitController@edit")->name("editUnit");
Route::post("/configuration/unit/delete/{id}", "MeasurementUnit@delete")->name("deleteUnit");


/* ================================ User Account Routes ================================ */
Route::get("/configuration/users", "UserController@show")->name("users.show");
Route::post("/configuration/user/create", "UserController@create")->name("user.create");
Route::get("/configuration/user/{username}", "UserController@detail")->name("user.detail");
Route::post("/configuration/user/edit/{username}", "UserController@update")->name("user.update");
Route::post("/configuration/user/delete/{username}", "UserController@delete")->name("user.delete");

/* ================================ Stock Entry Routes ================================ */
Route::get("/inventory/stock-entry", "StockEntryController@show")->name("showStockEntry");
Route::post("/inventory/stock-entry/store", "StockEntryController@store")->name("storeStockEntry");
Route::post("/inventory/stock-entry/edit/{id}", "StockEntryController@edit")->name("editStockEntry");
Route::post("/inventory/stock-entry/delete/{id}", "StockEntryController@delete")->name("deleteStockEntry");
Route::get("report/inventory-valuation", "InventoryController@valuation")->name("inventory.valuation");


/* ================================ POS Routes ================================ */
Route::get("/pos", "POSController@show")->name("showPOS");
Route::get('/pos/cart/test','CartController@test')->name('cart.test');
Route::get('/pos/cart/test','CartController@test')->name('cart.test');
Route::get('/pos/cart/details', 'CartController@details')->name('cart.details');
Route::get('/pos/cart/delete/all', 'CartController@deleteAll')->name('cart.deleteAll');
Route::delete('/pos/cart/delete/{id}', 'CartController@delete')->name('cart.delete');
Route::get('/pos/cart','CartController@all')->name('cart.all');
Route::get('/pos/cart/process', 'CartController@process')->name('cart.process');
Route::post('/pos/cart/sell','CartController@sell')->name('cart.sell');
Route::post('/pos/cart/store','CartController@add')->name('cart.add');
Route::patch('/pos/cart/update','CartController@update')->name('cart.update');
Route::get("/pos/sales/receipt", "CartController@receipt")->name("cart.salesReceipt");


/* ================================ Transaction Routes ================================ */
Route::get("/report/purchase-transactions", "ReportController@getPurchaseTransactions")->name("report.purchase");
Route::get("/report/sales-transactions", "ReportController@getSalesTransactions")->name("report.sales");


/* ================================ Inventory Backup ================================ */
/*Route::get("/inventory/backup", "BackupController@show")->name("backup.show");
Route::get("/configuration/backup-settings", "BackupController@settings")->name("backupSettings");
Route::post("/configuration/backup-settings/create", "BackupController@create")->name("backup.create");
Route::get("/backup", "BackupController@test")->name("backup.test");*/

/* ================================ Migrations ================================ */
Route::get("/configuration/migrate","MigrationController@show")->name("migrate.show");
Route::post("/configuration/migrate/store","MigrationController@store")->name("migrate.store");