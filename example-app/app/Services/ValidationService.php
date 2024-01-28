<?php


namespace App\Services;

use Illuminate\Support\Facades\Validator;

class ValidationService
{
    public function validatePostData($request)
    {
        return $this->validate($request, [
            'title' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'description' => 'required',
            'year' => 'required',
            'fileToUpload' => 'required|image|mimes:jpeg,png,jpg,gif|max:500000',
        ]);
    }

    protected function validate($data, $rules)
    {
        return Validator::make($data, $rules)->validate();
    }

}
