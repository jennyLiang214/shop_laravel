<?php

namespace App\Presenters;


class personalPresenter
{
    /**
     * 计算账户安全分数
     *
     * @return int
     * @author zhangyuchao
     */
    public function calculated()
    {
        $user = \Session::get('userInfo');
        $password = 25;
        $tel = $user->tel?25:0;
        $email = $user->email?25:0;
        $idCard = $user->id_number?25:0;
        return $password+$tel+$email+$idCard;
    }
}