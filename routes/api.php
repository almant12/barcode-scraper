
<?php
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    $validator = Validator::make([], [ // empty data to force failure
        'email' => 'required|email',
        'name'=> 'required'
    ]);

    throw new ValidationException($validator);
});
