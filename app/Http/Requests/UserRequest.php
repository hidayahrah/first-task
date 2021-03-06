<?php

namespace App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use App\User;
use Validator;

class UserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [ 
                'name' => 'required',
                'state' => 'nullable|string', 
                'phone' => 'nullable|string',
                'email' => 'required|email', 
                'password' => 'required', 
                'c_password' => 'required|same:password', 
            ];
    }
}
