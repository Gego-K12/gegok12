  <?php
  use Illuminate\Support\Facades\Route;
  use Gegok12\Fee\Http\Controllers\Api\FeesController;


  Route::group([
  'prefix' => 'v2', 
  'namespace' =>'Api' ,
    
  'middleware' => ['auth:sanctum'],
], function () {
    //Fees

    Route::get('/fees/paid/{student_id}',[FeesController::class,'paid']);

    Route::get('/fees/unpaid/{student_id}',[FeesController::class,'unpaid']);

    Route::get('/fees/show/{id}',[FeesController::class,'show']);

});