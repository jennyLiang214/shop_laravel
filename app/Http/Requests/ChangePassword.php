<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePassword extends FormRequest
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
            'oldPassword' => 'required|min:6',
            'newPassword' => 'required|min:6|confirmed',
            'newPassword_confirmation' => 'required|min:6',
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
            'oldPassword.required' => '旧密码不能为空!',
            'newPassword.required' => '新密码不能为空!',
            'oldPassword.min'      => '旧密码长度不可以小于6位!',
            'newPassword.min'      => '新密码长度不可以小于6位!',
            'newPassword_confirmation.required' => '确认密码不能为空!',
            'newPassword.confirmed'          => ' 两次密码输入不一致',
            'newPassword_confirmation.min'      => '确认密码长度不可以小于6位!',
        ];
    }
}
