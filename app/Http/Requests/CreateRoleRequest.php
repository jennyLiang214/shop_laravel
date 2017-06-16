<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateRoleRequest
 * @package App\Http\Requests
 */
class CreateRoleRequest extends FormRequest
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
            'name' => 'unique:roles,name'
        ];
    }

    /**
     * 验证消息
     *
     * @return array
     * @author: Luoyan
     */
    public function messages()
    {
        return [
            'name.unique' => '标识不能重复!'
        ];
    }
}
