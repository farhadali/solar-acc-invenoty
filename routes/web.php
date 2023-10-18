<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\AccountGroup;
use App\Models\AccountLedger;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\AccountGroupController;
use App\Http\Controllers\AccountHeadController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CostCenterController;
use App\Http\Controllers\StoreHouseController;
use App\Http\Controllers\AccountLedgerController;
use App\Http\Controllers\VoucherMasterController;
use App\Http\Controllers\AccountReportController;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\DamageAdjustmentController;
use App\Http\Controllers\WarrantyController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\BulkSmsController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\TableInfoController;
use App\Http\Controllers\MusakFourPointThreeController;
use App\Http\Controllers\ResturantSalesController;
use App\Http\Controllers\ResturantFormSettingController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\VatRuleController;
use App\Http\Controllers\StewardAllocationController;
use App\Http\Controllers\RestaurantCategorySettingController;
use App\Http\Controllers\WarrantyMasterController;
use App\Http\Controllers\ReplacementMasterController;
use App\Http\Controllers\TransectionTermsController;
use App\Http\Controllers\ServiceMasterController;
use App\Http\Controllers\IndividualReplaceMasterController;
use App\Http\Controllers\EasyVoucherController;
use App\Http\Controllers\InterProjectVoucherController;
use App\Http\Controllers\WItemReceiveFromSupplierController;





use App\Http\Controllers\BudgetsController;
use App\Http\Controllers\MaterialIssueController;






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

Route::get('/', 'App\Http\Controllers\FrontendController@index');

Auth::routes();


Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function() {

Route::resource('material-issue',MaterialIssueController::class);
Route::post('material-issue-setting', 'App\Http\Controllers\MaterialIssueController@Settings');
Route::get('material-issue-setting-modal', 'App\Http\Controllers\MaterialIssueController@formSettingAjax');
Route::get('available-qty-check-for-materail-issue-update', 'App\Http\Controllers\MaterialIssueController@checkQtyUpdateFoMaterialIssue');
Route::get('item-issue-edit-barcode-search', 'App\Http\Controllers\MaterialIssueController@itemIssueEditBarcodeSearch');


Route::get('material-issue/print/{id}', 'App\Http\Controllers\MaterialIssueController@Print');
Route::get('material-issue/challan/{id}', 'App\Http\Controllers\MaterialIssueController@challanPrint');
Route::get('net-material-issue-after-return/{id}', 'App\Http\Controllers\MaterialIssueController@issueAfterReturn');

//#########################
// Budgets Section Start
//#########################
Route::resource('budgets',BudgetsController::class);

//Budget Compare Cost Center Wise
Route::get('budget-compare', 'App\Http\Controllers\BudgetsController@budgetCompare');




/* HRM SECTION END*/















//IndividualReplaceMasterController Start
Route::resource('individual-replacement',IndividualReplaceMasterController::class);
Route::post('/individual-replacement/update', 'App\Http\Controllers\IndividualReplaceMasterController@update');
Route::post('individual-replacement-settings', 'App\Http\Controllers\IndividualReplaceMasterController@Settings');
Route::post('individual-replacement-wise-detail', 'App\Http\Controllers\IndividualReplaceMasterController@Detail');
Route::get('individual-replacement-out-report/{id}', [IndividualReplaceMasterController::class,'individualReplacementOutReport']);
Route::get('individual-replacement-customer-delivery-report/{id}', [IndividualReplaceMasterController::class,'individualReplacementCustomerDeliveryReport']);
Route::get('individual-replacement-in-report/{id}', [IndividualReplaceMasterController::class,'individualReplacementInReport']);
Route::get('individual-replacement-print/{id}', [IndividualReplaceMasterController::class,'individualReplacementPrint']);
//IndividualReplaceMasterController End

// WItemReceiveFromSupplierController Start
Route::resource('w-item-receive-from-supp',WItemReceiveFromSupplierController::class);
Route::post('/w-item-receive-from-supp/update', 'App\Http\Controllers\WItemReceiveFromSupplierController@update');
Route::post('w-item-receive-from-supp-settings', 'App\Http\Controllers\WItemReceiveFromSupplierController@Settings');
Route::post('w-item-receive-from-supp-wise-detail', 'App\Http\Controllers\WItemReceiveFromSupplierController@Detail');
Route::get('w-item-receive-from-supp-out-report/{id}', [WItemReceiveFromSupplierController::class,'individualReplacementOutReport']);
Route::get('w-item-receive-from-supp-customer-delivery-report/{id}', [WItemReceiveFromSupplierController::class,'individualReplacementCustomerDeliveryReport']);
Route::get('w-item-receive-from-supp-in-report/{id}', [WItemReceiveFromSupplierController::class,'individualReplacementInReport']);
Route::get('w-item-receive-from-supp-print/{id}', [WItemReceiveFromSupplierController::class,'individualReplacementPrint']);
// WItemReceiveFromSupplierController End
    
Route::resource('third-party-service',ServiceMasterController::class);
Route::post('third-party-service-settings', 'App\Http\Controllers\ServiceMasterController@Settings');
Route::post('third-party-service-wise-detail', 'App\Http\Controllers\ServiceMasterController@purchaseWiseDetail');
Route::get('third-party-service/print/{id}', 'App\Http\Controllers\ServiceMasterController@Print');
Route::post('third-party-service/update', 'App\Http\Controllers\ServiceMasterController@update');
Route::get('third-party-service-money-receipt/{id}', 'App\Http\Controllers\ServiceMasterController@moneyReceipt');

//ReplacementMasterController

Route::resource('item-replace',ReplacementMasterController::class);
Route::get('item-replace-setting-modal', 'App\Http\Controllers\ReplacementMasterController@formSettingAjax');

Route::post('item-replace-setting', 'App\Http\Controllers\ReplacementMasterController@Settings');
Route::get('item-replace-order-search', 'App\Http\Controllers\ReplacementMasterController@orderSearch');

 Route::get('item-replace-reset', 'App\Http\Controllers\ReplacementMasterController@reset');

Route::post('item-replace-order-details', 'App\Http\Controllers\ReplacementMasterController@purchaseOrderDetails');
Route::post('item-replace-invoice-wise-detail', 'App\Http\Controllers\ReplacementMasterController@invoiceWiseDetail');
Route::post('item-replace/update', 'App\Http\Controllers\ReplacementMasterController@update');
Route::get('item-replace/challan/{id}', 'App\Http\Controllers\ReplacementMasterController@challanPrint');
Route::get('item-replace/print/{id}', 'App\Http\Controllers\ReplacementMasterController@Print');
Route::get('item-replace-money-receipt/{id}', 'App\Http\Controllers\ReplacementMasterController@moneyReceipt');

//Resturant POS SECTION
Route::resource('table-info', TableInfoController::class);
Route::resource('steward-waiter', StewardAllocationController::class);
Route::resource('musak-four-point-three', MusakFourPointThreeController::class);
Route::post('musak-four-point-three-wise-detail',  'App\Http\Controllers\MusakFourPointThreeController@detail');

Route::get('restaurant-pos', 'App\Http\Controllers\ResturantSalesController@restaurantPos');
Route::get('kitchen-slip/{id}', 'App\Http\Controllers\ResturantSalesController@kitchenSlip');
Route::resource('kitchen', KitchenController::class);
Route::post('check_available_ingredients', 'App\Http\Controllers\KitchenController@check_available_ingredients');
Route::post('coocked_confirm_check', 'App\Http\Controllers\KitchenController@coocked_confirm_check');
Route::post('coocked-served-confirm', 'App\Http\Controllers\KitchenController@coockedServedConfirm');
Route::get('book_table_list_ajax', 'App\Http\Controllers\ResturantSalesController@book_table_list_ajax');



    

    //Admin Section start
    Route::resource('roles', RoleController::class);
    Route::resource('category-allocation', RestaurantCategorySettingController::class);
    
    Route::resource('warranty-manage', WarrantyMasterController::class);
    Route::get('warranty-setting-modal', 'App\Http\Controllers\WarrantyMasterController@formSettingAjax');
    Route::post('warranty-settings', 'App\Http\Controllers\WarrantyMasterController@warrantySettings');
    Route::get('barcode-warranty-search', 'App\Http\Controllers\WarrantyMasterController@barcodeWarrantySearch');
    Route::get('warranty-manage/print/{id}', 'App\Http\Controllers\WarrantyMasterController@print');
    Route::get('warranty-manage-reset', 'App\Http\Controllers\WarrantyMasterController@reset');
    Route::get('warranty-search', 'App\Http\Controllers\WarrantyMasterController@warrantySearch');
    Route::post('warranty-detail-search', 'App\Http\Controllers\WarrantyMasterController@warrantySearchDetail');
    Route::get('warranty-check', 'App\Http\Controllers\WarrantyMasterController@warrantyCheck')->name('warranty-check');


    
    Route::get('sms-send', 'App\Http\Controllers\BulkSmsController@index')->name('sms-send');
    Route::post('bulk-sms-send', 'App\Http\Controllers\BulkSmsController@store');
    Route::resource('users', UserController::class);
    Route::resource('social_media', SocialMediaController::class);
    Route::resource('branch', BranchController::class);
    Route::post('branch/update', 'App\Http\Controllers\BranchController@update');

    Route::resource('account-type', AccountHeadController::class);
    Route::post('account-type/update', 'App\Http\Controllers\AccountHeadController@update');
    Route::get('account-type-reset', 'App\Http\Controllers\AccountHeadController@reset');

    Route::resource('account-group', AccountGroupController::class);
    Route::post('account-group/update', 'App\Http\Controllers\AccountGroupController@update');
    Route::get('account-group-reset', 'App\Http\Controllers\AccountGroupController@reset');

    Route::resource('cost-center', CostCenterController::class);
    Route::post('cost-center/update', 'App\Http\Controllers\CostCenterController@update');
    Route::get('cost-center-chain/{id}', 'App\Http\Controllers\CostCenterController@csAuthorizationChain');
    Route::post('cost-center-authorization-chain', 'App\Http\Controllers\CostCenterController@csAuthorizationChainUpdate');

    Route::resource('item-category', ItemCategoryController::class);
    Route::post('item-category/update', 'App\Http\Controllers\ItemCategoryController@update');
    
    Route::resource('warranty', WarrantyController::class);
    Route::post('warranty/update', 'App\Http\Controllers\WarrantyController@update');

    Route::resource('item-information', InventoryController::class);
    Route::post('item-information/update', 'App\Http\Controllers\InventoryController@update');
    Route::post('file-upload', 'App\Http\Controllers\InventoryController@fileUpload');

    Route::get('item-wise-unit-conversion', 'App\Http\Controllers\InventoryController@itemWiseUnitConversion');
    Route::get('manufacture-comapany-search', 'App\Http\Controllers\InventoryController@showManufactureCompanys');
    Route::get('item-wise-unit-conversion-save', 'App\Http\Controllers\InventoryController@itemWiseUnitConversionSave');
    Route::get('item-wise-units', 'App\Http\Controllers\InventoryController@itemWiseUnits');
    Route::post('ajax-item-save', 'App\Http\Controllers\InventoryController@ajaxItemSave');
    Route::get('item-information-reset', 'App\Http\Controllers\InventoryController@reset');
    Route::get('item-purchase-search', 'App\Http\Controllers\InventoryController@itemPurchaseSearch');
    Route::get('lot-item-information', 'App\Http\Controllers\InventoryController@lotItemInformation')->name('lot-item-information');
    Route::get('lot-item-information-reset', 'App\Http\Controllers\InventoryController@lotReset');
    Route::get('item-sales-price-edit/{id}', 'App\Http\Controllers\InventoryController@salesPriceEdit');
    Route::post('item-sales-price-update', 'App\Http\Controllers\InventoryController@salesPriceUpdate');
    Route::get('labels-print', 'App\Http\Controllers\InventoryController@labelPrint')->name('labels-print');
    Route::post('barcode-print-store', 'App\Http\Controllers\InventoryController@barcodePrintStore');

    
    Route::resource('store-house', StoreHouseController::class);
    Route::post('store-house/update', 'App\Http\Controllers\StoreHouseController@update');

    Route::resource('account-ledger', AccountLedgerController::class);
    Route::post('account-ledger/update', 'App\Http\Controllers\AccountLedgerController@update');
    Route::post('ajax-ledger-save', 'App\Http\Controllers\AccountLedgerController@ajaxLedgerSave');
    Route::get('account-ledger-reset', 'App\Http\Controllers\AccountLedgerController@reset');

    Route::resource('purchase', PurchaseController::class);
    Route::post('purchase/update', 'App\Http\Controllers\PurchaseController@update');
    Route::post('purchase-wise-detail', 'App\Http\Controllers\PurchaseController@purchaseWiseDetail');
    Route::get('purchase-reset', 'App\Http\Controllers\PurchaseController@reset');
    Route::get('purchase/print/{id}', 'App\Http\Controllers\PurchaseController@purchasePrint');
    Route::post('purchase-settings', 'App\Http\Controllers\PurchaseController@purchaseSettings');
    Route::get('purchase-money-receipt/{id}', 'App\Http\Controllers\PurchaseController@moneyReceipt');
    Route::get('item-purchase-barcode-check', 'App\Http\Controllers\PurchaseController@itemPurchaseBarcodeCheck');

    Route::resource('purchase-order', PurchaseOrderController::class);
    Route::post('purchase-order/update', 'App\Http\Controllers\PurchaseOrderController@update');
    Route::get('purchase-order-reset', 'App\Http\Controllers\PurchaseOrderController@reset');
    Route::get('purchase-order/print/{id}', 'App\Http\Controllers\PurchaseOrderController@purchaseOrderPrint');
    Route::post('purchase-order-settings', 'App\Http\Controllers\PurchaseOrderController@purchaseOrderSettings');
    Route::get('purchase-pre-order-search', 'App\Http\Controllers\PurchaseOrderController@orderSearch');
    Route::post('purchase-pre-order-details', 'App\Http\Controllers\PurchaseOrderController@purchaseOrderDetails');

    Route::resource('sales-order', SalesOrderController::class);
    Route::post('sales-order/update', 'App\Http\Controllers\SalesOrderController@update');
   
    Route::get('sales-order-reset', 'App\Http\Controllers\SalesOrderController@reset');
    Route::get('sales-order-delete/{id}', 'App\Http\Controllers\SalesOrderController@destroy');
    Route::get('sales-order/print/{id}', 'App\Http\Controllers\SalesOrderController@SalesOrderPrint');
    Route::post('sales-order-settings', 'App\Http\Controllers\SalesOrderController@SalesOrderSettings');
    Route::get('purchase-pre-order-search', 'App\Http\Controllers\PurchaseOrderController@orderSearch');
    Route::post('purchase-pre-order-details', 'App\Http\Controllers\PurchaseOrderController@purchaseOrderDetails');
    
//Sales section Start
    Route::resource('sales', SalesController::class);
    Route::post('invoice-wise-detail', 'App\Http\Controllers\SalesController@invoiceWiseDetail');

    Route::post('sales/update', 'App\Http\Controllers\SalesController@update');
    Route::get('sales-reset', 'App\Http\Controllers\SalesController@reset');
    Route::get('sales/print/{id}', 'App\Http\Controllers\SalesController@Print');
    Route::get('sales/challan/{id}', 'App\Http\Controllers\SalesController@challanPrint');
    Route::get('net-sales-after-return/{id}', 'App\Http\Controllers\SalesController@salesAfterReturn');

    Route::post('sales-settings', 'App\Http\Controllers\SalesController@Settings');
    Route::get('sales-setting-modal', 'App\Http\Controllers\SalesController@formSettingAjax');


    
    
    Route::get('item-sales-search', 'App\Http\Controllers\SalesController@itemSalesSearch');
    Route::get('item-damage-search', 'App\Http\Controllers\SalesController@itemDamageSearch');
    Route::get('item-damage-search-edit', 'App\Http\Controllers\SalesController@itemDamageSearchEdit');
    Route::get('item-sales-barcode-search', 'App\Http\Controllers\SalesController@itemSalesBarcodeSearch');
    Route::post('pos-payment-row', 'App\Http\Controllers\SalesController@posPaymentRow');
    Route::post('pos-sales-save', 'App\Http\Controllers\SalesController@posSalesSave');
    Route::get('item-sales-edit-barcode-search', 'App\Http\Controllers\SalesController@itemSalesEditBarcodeSearch');
    Route::get('check-available-qty', 'App\Http\Controllers\SalesController@checkAvailableQty');
    Route::get('check-available-qty-update', 'App\Http\Controllers\SalesController@checkAvailableQtyUpdate');
    Route::get('check-available-qty-update-damage', 'App\Http\Controllers\SalesController@checkAvailableQtyUpdateDamage');
    Route::get('sales-money-receipt/{id}', 'App\Http\Controllers\SalesController@moneyReceipt');
    Route::get('pos-sales', 'App\Http\Controllers\SalesController@posSales');

    Route::post('category-wise-item', 'App\Http\Controllers\SalesController@categoryWiseItem');
    Route::post('hold-invoice-list', 'App\Http\Controllers\SalesController@holdInvoiceList');
//Sales section end


    
//Sales section Start
  //  Route::resource('restaurant', ResturantSalesController::class);
    Route::resource('restaurant-sales', ResturantSalesController::class);
    Route::post('restaurant-invoice-wise-detail', 'App\Http\Controllers\ResturantSalesController@invoiceWiseDetail');
    Route::get('restaurant-edit', 'App\Http\Controllers\ResturantSalesController@restaurantEdit');

    Route::post('restaurant-sales/update', 'App\Http\Controllers\ResturantSalesController@update');
    Route::get('restaurant-sales-reset', 'App\Http\Controllers\ResturantSalesController@reset');
    Route::get('restaurant-sales/print/{id}', 'App\Http\Controllers\ResturantSalesController@Print');
    Route::get('restaurant-sales/challan/{id}', 'App\Http\Controllers\ResturantSalesController@challanPrint');
    Route::post('restaurant-sales-settings', 'App\Http\Controllers\ResturantSalesController@Settings');
    Route::get('restaurant-sales-setting-modal', 'App\Http\Controllers\ResturantSalesController@formSettingAjax');
    Route::get('item-restaurant-sales-search', 'App\Http\Controllers\ResturantSalesController@itemSalesSearch');
  //  Route::get('item-damage-search', 'App\Http\Controllers\ResturantSalesController@itemDamageSearch');
    Route::get('item-restaurant-sales-barcode-search', 'App\Http\Controllers\ResturantSalesController@itemSalesBarcodeSearch');
    Route::post('pos-payment-row', 'App\Http\Controllers\ResturantSalesController@posPaymentRow');
    Route::post('pos-restaurant-sales-save', 'App\Http\Controllers\ResturantSalesController@posSalesSave');
    Route::get('item-restaurant-sales-edit-barcode-search', 'App\Http\Controllers\ResturantSalesController@itemSalesEditBarcodeSearch');
    Route::get('restaurant-sales-check-available-qty', 'App\Http\Controllers\ResturantSalesController@checkAvailableQty');
    Route::get('restaurant-sales-check-available-qty-update', 'App\Http\Controllers\ResturantSalesController@checkAvailableQtyUpdate');
    Route::get('restaurant-sales-check-available-qty-update-damage', 'App\Http\Controllers\ResturantSalesController@checkAvailableQtyUpdateDamage');
    Route::get('restaurant-sales-money-receipt/{id}', 'App\Http\Controllers\ResturantSalesController@moneyReceipt');
    Route::get('pos-restaurant-sales', 'App\Http\Controllers\ResturantSalesController@posSales');
    Route::post('restaurant-sales-category-wise-item', 'App\Http\Controllers\ResturantSalesController@categoryWiseItem');
    Route::post('restaurant-sales-hold-invoice-list', 'App\Http\Controllers\ResturantSalesController@holdInvoiceList');



    Route::post('restaurant-category-wise-item', 'App\Http\Controllers\ResturantSalesController@categoryWiseItem');
    Route::get('recent-restaurnt-sales-list', 'App\Http\Controllers\ResturantSalesController@recentRestaurntSalesList');
//Sales section end




    Route::resource('damage', DamageAdjustmentController::class);
    Route::post('damage/update', 'App\Http\Controllers\DamageAdjustmentController@update');
    Route::get('damage-reset', 'App\Http\Controllers\DamageAdjustmentController@reset');
    Route::get('damage/print/{id}', 'App\Http\Controllers\DamageAdjustmentController@Print');
    Route::post('damage-settings', 'App\Http\Controllers\DamageAdjustmentController@Settings');
    Route::get('damage-setting-modal', 'App\Http\Controllers\DamageAdjustmentController@formSettingAjax');

    Route::resource('production', ProductionController::class);
    Route::post('production/update', 'App\Http\Controllers\ProductionController@update');
    Route::get('production-reset', 'App\Http\Controllers\ProductionController@reset');
    Route::get('production/print/{id}', 'App\Http\Controllers\ProductionController@Print');
    Route::get('transfer-production/print/{id}', 'App\Http\Controllers\ProductionController@Print');
    Route::get('production/stock-in/{id}', 'App\Http\Controllers\ProductionController@PrintStockIn');


    Route::resource('transfer', TransferController::class);
    Route::post('transfer/update', 'App\Http\Controllers\TransferController@update');
    Route::get('transfer-reset', 'App\Http\Controllers\TransferController@reset');
    Route::get('transfer/print/{id}', 'App\Http\Controllers\TransferController@Print');
    Route::get('transfer/stock-in/{id}', 'App\Http\Controllers\TransferController@PrintStockIn');
    Route::get('transfer/stock-out/{id}', 'App\Http\Controllers\TransferController@PrintStockOut');

   

    

    Route::get('transfer-production/stock-out/{id}', 'App\Http\Controllers\ProductionController@PrintStockOut');
    Route::get('production-setting-modal', 'App\Http\Controllers\ProductionController@formSettingAjax');
    Route::post('production-form-settings', 'App\Http\Controllers\ProductionController@Settings');
   
    
    
    

    Route::resource('sales-return', SalesReturnController::class);
    Route::post('sales-return/update', 'App\Http\Controllers\SalesReturnController@update');
    Route::get('sales-return-reset', 'App\Http\Controllers\SalesReturnController@reset');
    Route::get('sales-return/print/{id}', 'App\Http\Controllers\SalesReturnController@Print');
    Route::post('sales-return-settings', 'App\Http\Controllers\SalesReturnController@Settings');
    Route::get('sales-return-setting-modal', 'App\Http\Controllers\SalesReturnController@formSettingAjax');
    Route::get('sales-order-search', 'App\Http\Controllers\SalesReturnController@orderSearch');
    Route::get('check-sales-return-available-qty', 'App\Http\Controllers\SalesReturnController@checkAvailableSalesQty');
    Route::post('sales-order-details', 'App\Http\Controllers\SalesReturnController@salesOrderDetails');
    Route::get('sales-return-money-receipt/{id}', 'App\Http\Controllers\SalesReturnController@moneyReceipt');
    Route::post('sales-return-detail', 'App\Http\Controllers\SalesReturnController@salesReturnDetail');
    
    

    Route::resource('purchase-return', PurchaseReturnController::class);
    Route::post('purchase-return/update', 'App\Http\Controllers\PurchaseReturnController@update');
    Route::get('purchase-return-reset', 'App\Http\Controllers\PurchaseReturnController@reset');
    Route::get('purchase-return/print/{id}', 'App\Http\Controllers\PurchaseReturnController@purchasePrint');
    Route::post('purchase-return-settings', 'App\Http\Controllers\PurchaseReturnController@purchaseSettings');
    Route::get('purchase-order-search', 'App\Http\Controllers\PurchaseReturnController@purchaseOrderSearch');
    Route::post('purchase-order-details', 'App\Http\Controllers\PurchaseReturnController@purchaseOrderDetails');
    Route::get('purchase-return-money-receipt/{id}', 'App\Http\Controllers\PurchaseReturnController@moneyReceipt');

    Route::resource('unit', UnitsController::class);
    Route::post('unit/update', 'App\Http\Controllers\UnitsController@update');
    Route::get('unit-reset', 'App\Http\Controllers\UnitsController@reset');


    Route::resource('vat-rules', VatRuleController::class);
    Route::post('vat-rules/update', 'App\Http\Controllers\VatRuleController@update');
    Route::get('vat-rules-reset', 'App\Http\Controllers\VatRuleController@reset');

    Route::resource('transection_terms', TransectionTermsController::class);
    Route::post('transection_terms/update', 'App\Http\Controllers\TransectionTermsController@update');
    Route::get('transection_terms-reset', 'App\Http\Controllers\TransectionTermsController@reset');

    Route::resource('voucher', VoucherMasterController::class);

    Route::resource('easy-voucher', EasyVoucherController::class);
    Route::resource('inter-project-voucher', InterProjectVoucherController::class);

    Route::post('voucher/update', 'App\Http\Controllers\VoucherMasterController@update');
    Route::get('voucher/print/{id}', 'App\Http\Controllers\VoucherMasterController@voucherPrint');
    Route::get('voucher-main-print', 'App\Http\Controllers\VoucherMasterController@voucherMainPrint');
    Route::get('voucher-detail-print', 'App\Http\Controllers\VoucherMasterController@voucherDetailPrint');
    Route::get('voucher-reset', 'App\Http\Controllers\VoucherMasterController@reset');
    Route::get('money-receipt-print/{id}', 'App\Http\Controllers\VoucherMasterController@moneyReceiptPrint');
    Route::get('money-payment-receipt/{id}', 'App\Http\Controllers\VoucherMasterController@moneyPaymentReceiptPrint');

    Route::post('master-base-detils','App\Http\Controllers\VoucherMasterController@masterBseDetails');
    Route::get('cash-receive','App\Http\Controllers\VoucherMasterController@cashReceive');
    Route::get('bank-receive','App\Http\Controllers\VoucherMasterController@bankReceive');
    Route::get('cash-payment','App\Http\Controllers\VoucherMasterController@cashPayment');
    Route::get('bank-payment','App\Http\Controllers\VoucherMasterController@bankPayment');



    Route::post('voucher-save','App\Http\Controllers\VoucherMasterController@voucherSave');


    //Account Report Section 
    Route::get('ledger-report','App\Http\Controllers\AccountReportController@ledgerReprt')->name('ledger-report');
    Route::get('ledger-report-show','App\Http\Controllers\AccountReportController@ledgerReprtShow');
    Route::get('group-ledger','App\Http\Controllers\AccountReportController@groupLedger')->name('group-ledger');

    Route::post('group-base-ledger-report','App\Http\Controllers\AccountReportController@groupBaseLedgerReport');
    Route::get('group-base-ledger-filter-reset','App\Http\Controllers\AccountReportController@groupBaseLedgerFilterReset');
    Route::get('LedgerReportFilterReset','App\Http\Controllers\AccountReportController@LedgerReportFilterReset');

    Route::post('ledger-summary-report','App\Http\Controllers\AccountReportController@ledgerSummaryReport')->name('ledger-summary-report');
    Route::get('ledger-summary-filter-reset','App\Http\Controllers\AccountReportController@ledgerSummaryFilterReset');
    Route::get('filter-ledger-summary','App\Http\Controllers\AccountReportController@filterLedgerSummarray');



    Route::any('trail-balance','App\Http\Controllers\AccountReportController@trailBalance')->name('trail-balance');
    Route::any('trail-balance-report','App\Http\Controllers\AccountReportController@trailBalanceReport');
    Route::get('trail-balance-filter-reset','App\Http\Controllers\AccountReportController@trailBalanceReportFilterReset');

    Route::get('income-statement','App\Http\Controllers\AccountReportController@incomeStatement')->name('income-statement');
    Route::get('income-statement-filter-reset','App\Http\Controllers\AccountReportController@incomeStatementFilterReset');
    Route::post('income-statement-report','App\Http\Controllers\AccountReportController@incomeStatementReport');
    Route::post('income-statement-settings','App\Http\Controllers\AccountReportController@incomeStatementSettings');

    Route::get('balance-sheet','App\Http\Controllers\AccountReportController@balanceSheet')->name('balance-sheet');
    Route::get('balance-sheet-filter-reset','App\Http\Controllers\AccountReportController@balanceSheetFilterReset');
    Route::get('balance-sheet-report','App\Http\Controllers\AccountReportController@balanceSheetReport');

    Route::get('work-sheet','App\Http\Controllers\AccountReportController@workSheet')->name('work-sheet');
    Route::get('work-sheet-filter-reset','App\Http\Controllers\AccountReportController@workSheetFilterReset');
    Route::get('work-sheet-report','App\Http\Controllers\AccountReportController@workSheetReport');

    Route::get('cash-book','App\Http\Controllers\AccountReportController@cashBook')->name('cash-book');
    Route::get('cash-book-filter-reset','App\Http\Controllers\AccountReportController@cashBookFilterReset');
    Route::post('cash-book-report','App\Http\Controllers\AccountReportController@cashBookReport');

    Route::get('day-book','App\Http\Controllers\AccountReportController@dayBook')->name('day-book');
    Route::get('day-book-filter-reset','App\Http\Controllers\AccountReportController@dayBookFilterReset');
    Route::post('day-book-report','App\Http\Controllers\AccountReportController@dayBookReport');


    //Resturant Report Start
     Route::get('day-wise-summary-report','App\Http\Controllers\AccountReportController@dayWiseSummaryReportFilter');
    Route::get('day-wise-summary-report-filter-reset','App\Http\Controllers\AccountReportController@dayWiseSummaryReportFilterReset');
    Route::post('day-wise-summary-report','App\Http\Controllers\AccountReportController@dayWiseSummaryReport');

    Route::get('item-sales-report','App\Http\Controllers\AccountReportController@itemSalesReportFilter');
    Route::post('item-sales-report','App\Http\Controllers\AccountReportController@itemSalesReport');
    Route::get('item-sales-report-filter-reset','App\Http\Controllers\AccountReportController@itemSalesReportFilterReset');
    
    Route::get('detail-item-sales-report','App\Http\Controllers\AccountReportController@detailItemSalesReportFilter');
    Route::post('detail-item-sales-report','App\Http\Controllers\AccountReportController@detailItemSalesReport');
    Route::get('detail-item-sales-report-filter-reset','App\Http\Controllers\AccountReportController@detailItemSalesReportFilterReset');

    //Resturant Report End

    Route::get('user-wise-collection-payment','App\Http\Controllers\AccountReportController@userReceiptPayment');
    Route::get('user-wise-collection-payment-filter-reset','App\Http\Controllers\AccountReportController@userReceiptPaymentFilterReset');
    Route::post('user-wise-collection-payment-report','App\Http\Controllers\AccountReportController@userReceiptPaymentReport');

    Route::get('date-wise-invoice-print','App\Http\Controllers\AccountReportController@dateWiseInvoice');
    Route::get('date-wise-invoice-print-filter-reset','App\Http\Controllers\AccountReportController@dateWiseInvoiceFilterReset');
    Route::post('date-wise-invoice-print-report','App\Http\Controllers\AccountReportController@dateWiseInvoiceReport');

    Route::get('date-wise-restaurant-invoice-print','App\Http\Controllers\AccountReportController@dateWiseRestaurantInvoice');
    Route::get('date-wise-restaurant-invoice-print-filter-reset','App\Http\Controllers\AccountReportController@dateWiseRestaurantInvoiceFilterReset');
    Route::post('date-wise-restaurant-invoice-print-report','App\Http\Controllers\AccountReportController@dateWiseRestaurantInvoiceReport');

    Route::get('bank-book','App\Http\Controllers\AccountReportController@bankBook')->name('bank-book');
    Route::get('bank-book-filter-reset','App\Http\Controllers\AccountReportController@bankBookFilterReset');
    Route::post('bank-book-report','App\Http\Controllers\AccountReportController@bankBookReport');

    Route::get('receipt-payment','App\Http\Controllers\AccountReportController@receiptPayment')->name('receipt-payment');
    Route::get('receipt-payment-filter-reset','App\Http\Controllers\AccountReportController@receiptPaymentFilterReset');
    Route::post('receipt-payment-report','App\Http\Controllers\AccountReportController@receiptPaymentReport');
    
    //Searching section 
    Route::any('ledger-search','App\Http\Controllers\AccountLedgerController@ledger_search');
    Route::any('main-ledger-search','App\Http\Controllers\AccountLedgerController@mainLedgerSearch');
    Route::any('type_base_group','App\Http\Controllers\AccountLedgerController@type_base_group');
    Route::any('group-base-ledger','App\Http\Controllers\AccountLedgerController@groupBaseLedger');
    Route::any('group-base-sms-ledger','App\Http\Controllers\AccountLedgerController@groupBaseSmsLedger');
    Route::any('group-base-bill-party-ledger','App\Http\Controllers\AccountLedgerController@groupBaseBillParty');
    
    Route::any('group-base-ledger-purchase-statement','App\Http\Controllers\AccountLedgerController@groupBaseLedgerPurchaseStatement');
    Route::any('chart-of-account','App\Http\Controllers\AccountReportController@chartOfAccount')->name('chart-of-account');
    
    
    
    
    //################################
    //  Inventory Report Section Start
    //################################
    

    Route::post('report-bill-party-statement','App\Http\Controllers\InventoryReportController@reportBillOfPartyStatement');
    Route::get('bill-party-statement','App\Http\Controllers\InventoryReportController@filterBillOfPartyStatement');
    Route::get('reset-bill-party-statement','App\Http\Controllers\InventoryReportController@resetBillOfPartyStatement');


    Route::get('filter-item-history','App\Http\Controllers\InventoryReportController@filterItemHistory');
    Route::post('report-item-history','App\Http\Controllers\InventoryReportController@reportItemHistory');
Route::get('reset-item-history','App\Http\Controllers\InventoryReportController@resetItemHistory');
Route::get('item-history-update','App\Http\Controllers\InventoryReportController@itemHistoryUpdate');
    
    

    Route::post('report-barcode-history','App\Http\Controllers\InventoryReportController@reportBarcodeHistory');
    Route::get('barcode-history','App\Http\Controllers\InventoryReportController@filterBarcodeHistory');
    Route::get('reset-barcode-history','App\Http\Controllers\InventoryReportController@resetBarcodeHistory');   

    Route::post('report-date-wise-purchase','App\Http\Controllers\InventoryReportController@reportDateWisePurchaseStatement');
    Route::get('date-wise-purchase','App\Http\Controllers\InventoryReportController@filterDateWisePurchaseStatement');
    Route::get('reset-date-wise-purchase','App\Http\Controllers\InventoryReportController@resetDateWisePurchaseStatement'); 



    Route::post('report-date-wise-purchase-return','App\Http\Controllers\InventoryReportController@reportDateWisePurchaseReturnStatement');
    Route::get('purchase-return-detail','App\Http\Controllers\InventoryReportController@filterDateWisePurchaseReturnStatement');
    Route::get('reset-date-wise-purchase-return','App\Http\Controllers\InventoryReportController@resetDateWisePurchaseReturnStatement');
    Route::any('group-base-ledger-purchase-return','App\Http\Controllers\AccountLedgerController@groupBaseLedgerPurchaseReturnStatement');

    Route::post('report-date-wise-sales','App\Http\Controllers\InventoryReportController@reportDateWiseSalesStatement');
    Route::get('date-wise-sales','App\Http\Controllers\InventoryReportController@filterDateWiseSalesStatement');
    Route::get('reset-date-wise-sales','App\Http\Controllers\InventoryReportController@resetDateWiseSalesStatement');

    Route::post('report-actual-sales','App\Http\Controllers\InventoryReportController@reportActualSales');
    Route::get('filter-actual-sales','App\Http\Controllers\InventoryReportController@filterActualSales');
    Route::get('reset-actual-sales','App\Http\Controllers\InventoryReportController@resetActualSales');

    Route::get('date-wise-restaurant-sales','App\Http\Controllers\InventoryReportController@filterDateWiseRestaurantSalesStatement');
    Route::post('report-date-wise-restaurant-sales','App\Http\Controllers\InventoryReportController@reportDateWiseRestaurantSalesStatement');
    Route::get('reset-date-wise-restaurant-sales','App\Http\Controllers\InventoryReportController@resetDateWiseRestaurantSalesStatement');



    Route::any('group-base-ledger-sales','App\Http\Controllers\AccountLedgerController@groupBaseLedgerSalesStatement');

    Route::post('report-date-wise-sales-return','App\Http\Controllers\InventoryReportController@reportDateWiseSalesReturnStatement');
    Route::get('sales-return-detail','App\Http\Controllers\InventoryReportController@filterDateWiseSalesReturnStatement');
    Route::get('reset-date-wise-sales-return','App\Http\Controllers\InventoryReportController@resetDateWiseSalesReturnStatement');
    Route::any('group-base-ledger-sales-return','App\Http\Controllers\AccountLedgerController@groupBaseLedgerSalesReturnStatement');

    Route::post('report-stock-possition','App\Http\Controllers\InventoryReportController@reportStockPossition');
    Route::get('stock-possition','App\Http\Controllers\InventoryReportController@filterStockPossition');
    Route::get('reset-stock-possition','App\Http\Controllers\InventoryReportController@resetStockPossition');
    Route::get('stock-possition-cat-item','App\Http\Controllers\InventoryReportController@stockPossitionCatItem');


    Route::post('report-stock-ledger','App\Http\Controllers\InventoryReportController@reportStockLedger');
    Route::get('stock-ledger','App\Http\Controllers\InventoryReportController@filterStockLedger');
    Route::get('reset-stock-ledger','App\Http\Controllers\InventoryReportController@resetStockLedger');

    Route::post('report-stock-ledger-history','App\Http\Controllers\InventoryReportController@reportStockLedgerHistory');
    Route::get('stock-ledger-history','App\Http\Controllers\InventoryReportController@filterStockLedgerHistory');
    Route::get('reset-stock-ledger-history','App\Http\Controllers\InventoryReportController@resetStockLedgerHistory');


    Route::get('stock-ledger-cat-item','App\Http\Controllers\InventoryReportController@stockLedgerCatItem');

    Route::post('report-single-stock-ledger','App\Http\Controllers\InventoryReportController@reportSingleStockLedger');
    Route::get('single-stock-ledger','App\Http\Controllers\InventoryReportController@filterSingleStockLedger');
    Route::get('reset-single-stock-ledger','App\Http\Controllers\InventoryReportController@resetSingleStockLedger');

    Route::post('report-stock-value','App\Http\Controllers\InventoryReportController@reportStockValue');
    Route::get('stock-value','App\Http\Controllers\InventoryReportController@filterStockValue');
    Route::get('reset-stock-value','App\Http\Controllers\InventoryReportController@resetStockValue');
    Route::get('stock-value-cat-item','App\Http\Controllers\InventoryReportController@stockValueCatItem');

    Route::post('report-stock-value-register','App\Http\Controllers\InventoryReportController@reportStockValueRegister');
    Route::get('stock-value-register','App\Http\Controllers\InventoryReportController@filterStockValueRegister');
    Route::get('reset-stock-value-register','App\Http\Controllers\InventoryReportController@resetStockValueRegister');
    Route::get('stock-value-register-cat-item','App\Http\Controllers\InventoryReportController@stockValueRegisterCatItem');

    Route::post('report-gross-profit','App\Http\Controllers\InventoryReportController@reportGrossProfit');
    Route::get('gross-profit','App\Http\Controllers\InventoryReportController@filterGrossProfit');
    Route::get('reset-gross-profit','App\Http\Controllers\InventoryReportController@resetGrossProfit');
    Route::get('gross-profit-cat-item','App\Http\Controllers\InventoryReportController@grossProfitCatItem');

    Route::post('report-expired-item','App\Http\Controllers\InventoryReportController@reportExpiredItem');
    Route::get('expired-item','App\Http\Controllers\InventoryReportController@filterExpiredItem');
    Route::get('reset-expired-item','App\Http\Controllers\InventoryReportController@resetExpiredItem');
    Route::get('expired-item-cat-item','App\Http\Controllers\InventoryReportController@expiredItemCatItem');

    Route::get('shortage-item','App\Http\Controllers\InventoryReportController@filterShortageItem');
    Route::post('report-shortage-item','App\Http\Controllers\InventoryReportController@reportShortageItem');
    Route::get('reset-shortage-item','App\Http\Controllers\InventoryReportController@resetShortageItem');
    Route::get('shortage-item-cat-item','App\Http\Controllers\InventoryReportController@shortageItemCatItem');

    

    //################################
    //  Inventory Report Section End
    //################################
    

    //Admin section end

    //Admin section Route Controller
    Route::get('admin-settings','App\Http\Controllers\GeneralSettingsController@settings')->name('admin-settings');
    Route::get('admin-truncate','App\Http\Controllers\GeneralSettingsController@tableTruncate')->name('admin-truncate');
    Route::post('admin-settings-store','App\Http\Controllers\GeneralSettingsController@settingsSave')->name('admin-settings-store');
    Route::post('_lock_action','App\Http\Controllers\GeneralSettingsController@lockAction');
    Route::post('all-lock','App\Http\Controllers\GeneralSettingsController@allLockSystem');
    Route::get('all-lock','App\Http\Controllers\GeneralSettingsController@allLock')->name('all-lock');
    Route::get('lock-reset','App\Http\Controllers\GeneralSettingsController@lockReset');
    Route::get('database-backup','App\Http\Controllers\GeneralSettingsController@databaseBackup')->name('database-backup');


    Route::get('invoice-prefix','App\Http\Controllers\GeneralSettingsController@invoicePrefix')->name('invoice-prefix');
    Route::post('invoice-prefix-store','App\Http\Controllers\GeneralSettingsController@invoicePrefixStore');






});

