<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
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
            'tel'      => 'required',
            'password' => 'required',
            'captcha'  => 'required|captcha',
        ];
    }
    /**
     * 消息提示转换
     *
     * @return array
     * @author: Luoyan
     */
    public function messages()
    {
        return [
            'tel.required'      => '手机号码不能空!',
            'password.required' => '密码号码不能空!',
            'captcha.required'   => '验证码不能为空!',
            'captcha.captcha'   => '验证码错误!'
        ];
    }
}