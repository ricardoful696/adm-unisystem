<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnterprisePayment;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\AdmController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\EmpresaSession;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use App\Http\Controllers\ErroController;
use App\Http\Controllers\DevController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\ReportController;

    Route::prefix('api')->group(function () {
        require base_path('routes/api.php');
    });

    Route::get('/', function () {
        return redirect()->route('selectEmp');
    });

    Route::get('/login', [LoginController::class, 'selectEmp'])->name('selectEmp');
    Route::get('/firstAcessView', [LoginController::class, 'firstAcessView'])->name('firstAcessView');
    Route::post('/firstAcess', [LoginController::class, 'searchUser'])->name('firstAcess');
    Route::post('/firstAcessSubmit', [LoginController::class, 'firstAcessSubmit'])->name('firstAcessSubmit');

    Route::middleware(EmpresaSession::class)->group(function () {

            Route::get('/erro', [ErroController::class, 'show'])->name('erro');

            
            Route::get('/{empresa}/login', [LoginController::class, 'showLoginForm'])->name('showLoginForm');
            Route::post('/{empresa}/login', [LoginController::class, 'login'])->name('login.submit');
           
    });

    Route::middleware(RedirectIfNotAuthenticated::class)->group(function () {
                Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
                Route::get('/home', [DashboardController::class, 'home'])->name('home');
            
                Route::get('/sidebar', [AdmController::class, 'view'])->name('view');
                Route::get('/calendar', [AdmController::class, 'viewCalendar'])->name('viewCalendar');
                Route::get('/getProduct/{categoriaId}', [AdmController::class, 'getProduct'])->name('getProduct');
                Route::get('/getCalendario/{produtoId}', [AdmController::class, 'getCalendario'])->name('getCalendario');
                Route::get('/getAllCalendar', [AdmController::class, 'getAllCalendar'])->name('getAllCalendar');
            //CONTA
                Route::get('/myAccount', [AccountController::class, 'myAccount'])->name('myAccount');
                Route::post('/myAccountUpdate', [AccountController::class, 'myAccountUpdate'])->name('myAccountUpdate');
            
            //EMPRESA
                Route::get('/enterpriseData', [EmpresaController::class, 'enterpriseDataView'])->name('enterpriseDataView');
                Route::post('/enterpriseDataUpdate', [EmpresaController::class, 'enterpriseDataUpdate'])->name('enterpriseDataUpdate');
                Route::get('/enterpriseLayout', [EmpresaController::class, 'enterpriseLayoutView'])->name('enterpriseLayoutView');
                Route::post('/saveLayout', [EmpresaController::class, 'saveLayout'])->name('saveLayout');
        
            
            //CAMPANHA
                Route::get('/newCampaignView', [CampaignController::class, 'newCampaignView'])->name('newCampaignView');
                Route::get('/campaignView', [CampaignController::class, 'campaignView'])->name('campaignView');
                Route::post('/saveCampaign', [CampaignController::class, 'saveCampaign'])->name('saveCampaign');
                Route::get('/editCampaign/{campanhaId}', [CampaignController::class, 'editCampaign'])->name('editCampaign');
                Route::post('/campaignUpdate', [CampaignController::class, 'campaignUpdate'])->name('campaignUpdate');
                Route::post('/getCampaignDetails', [CampaignController::class, 'getCampaignDetails'])->name('getCampaignDetails');
                Route::post('/getCoupons', [CampaignController::class, 'getCoupons'])->name('getCoupons');
        
            //PRODUTOS
                Route::get('/newProduct', [ProductController::class, 'newProductView'])->name('newProductView');
                Route::get('/productView', [ProductController::class, 'productView'])->name('productView');
                Route::post('/saveProduct', [ProductController::class, 'saveProduct'])->name('saveProduct');
                Route::post('/updateCalendarProduct', [ProductController::class, 'updateCalendarProduct'])->name('updateCalendarProduct');
                Route::get('/editProduct/{produtoId}', [ProductController::class, 'editProduct'])->name('editProduct');
                Route::post('/productUpdate', [ProductController::class, 'productUpdate'])->name('productUpdate');
                Route::get('/categoryView', [ProductController::class, 'categoryView'])->name('categoryView');
                Route::post('/categorySave', [ProductController::class, 'categorySave'])->name('categorySave');
                Route::post('/categoryEdit', [ProductController::class, 'categoryEdit'])->name('categoryEdit');
                Route::post('/primaryProductSave', [ProductController::class, 'primaryProductSave'])->name('primaryProductSave');
                
            //LOTES
                Route::get('/newBatchView', [BatchController::class, 'newBatchView'])->name('newBatchView');
                Route::get('/batchView', [BatchController::class, 'batchView'])->name('batchView');
                Route::post('/saveBatch', [BatchController::class, 'saveBatch'])->name('saveBatch');
                Route::get('/editBatch/{produtoId}', [BatchController::class, 'editBatch'])->name('editBatch');
                Route::post('/batchUpdate', [BatchController::class, 'batchUpdate'])->name('batchUpdate');
                Route::post('/getCategory', [BatchController::class, 'getCategory'])->name('getCategory');
                Route::post('/getDetails', [BatchController::class, 'getDetails'])->name('getDetails');

            //CONFIGURACOES
                Route::get('/enterpriseConfig', [ConfigurationController::class, 'configurationView'])->name('configurationView');
                Route::post('/saveEnterpriseConfig', [ConfigurationController::class, 'saveConfiguration'])->name('saveConfiguration');
                Route::get('/enterprisePaymentConfig', [ConfigurationController::class, 'enterprisePaymentView'])->name('enterprisePaymentView');
                Route::post('/saveEnterprisePaymentConfig', [ConfigurationController::class, 'savePaymentConfiguration'])->name('savePaymentConfiguration');
                Route::post('/uploadCertificate', [ConfigurationController::class, 'uploadCertificate'])->name('uploadCertificate');
                Route::post('/saveEfiConfigPayment', [ConfigurationController::class, 'saveEfiConfigPayment'])->name('saveEfiConfigPayment');
                Route::post('/save-pagarme-config', [ConfigurationController::class, 'savePagarmeConfig'])->name('savePagarmeConfigPayment');
            
            //EMPRESA PAGAMENTO
                Route::get('/enterprisePaymentView', [EnterprisePayment::class, 'enterprisePaymentView'])->name('enterprisePayment');

            //RELATORIO
                Route::get('/reportView', [ReportController::class, 'reportView'])->name('reportView');    
                Route::post('/generateReport', [ReportController::class, 'generateReport'])->name('generateReport');  
                
            //DEVELOPER
                Route::get('/enterpriseDevConfig', [DevController::class, 'entepriseDevView'])->name('enterpriseDevConfig');
                Route::post('/saveEnterpriseDev', [DevController::class, 'saveEnterpriseDev'])->name('saveEnterpriseDev');
                Route::get('/userDevConfig', [DevController::class, 'userDevView'])->name('userDevConfig');
                Route::post('/saveUserDev', [DevController::class, 'saveUserDev'])->name('saveUserDev');
                Route::get('/revDevConfig', [DevController::class, 'revDevView'])->name('revDevConfig');
                Route::get('/createCalendar', [DevController::class, 'createCalendar'])->name('createCalendar');
                Route::post('/newCalendarSave', [DevController::class, 'newCalendarSave'])->name('newCalendarSave');
                Route::get('/viewTeste', [AdmController::class, 'viewTeste'])->name('viewTeste');
                //Route::get('/getCalendario', [AdmController::class, 'getCalendario']);
                Route::post('/updateCalendario/{id}', [AdmController::class, 'updateCalendario']);
    });