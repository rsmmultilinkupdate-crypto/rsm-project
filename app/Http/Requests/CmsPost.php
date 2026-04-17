<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CmsPost extends FormRequest
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
            'title' => 'bail|required|unique:blogs|max:60',
            'description' => 'required',
            'user_id' => 'required|integer|exists:users,id',
            'is_active' => 'required|boolean',
        ];
    }
}
