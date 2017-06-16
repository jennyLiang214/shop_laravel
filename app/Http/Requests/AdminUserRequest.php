<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
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
            'tel'          => 'required|min:11|max:11',
            'nickname'     => 'required',
            'password'     => 'required|min:6',
            'rel_password' => 'required|min:6',
        ];
    }

    /**
     * 消息转换
     *
     * @return array
     * @author zhangyuchao
     */
    public function messages()
    {
        return [
            'tel.required'      => '手机号码不能空!',
            'nickname.required'      => '昵称不能为空!',
            'password.required' => '密码号码不能空!',
            'password.min' => '密码不可以小于6位',
            'rel_password.required' => '确认密码不能为空!',
            'rel_password.min' => '确认密码不可以小于6位!',
        ];
    }
}
