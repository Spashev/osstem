<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|min:1', 
            'price' => 'required', 
            'currency' => 'required|string', 
            'quantity' => 'required', 
            'description' => 'required|string|min:3', 
            'images' => 'required',
            'is_published' => 'required',
            'code' => 'required'
        ];
    }
}
