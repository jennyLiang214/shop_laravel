<!--头像 -->
<div class="user-infoPic">

    <div class="filePic">
        <input type="file" id="img" class="inputPic" allowexts="gif,jpeg,jpg,png,bmp" accept="image/*">
        <img class="am-circle am-img-thumbnail" src="@if(empty(\Session::get('userInfo')->avatar))/images/getAvatar.do.jpg @else {{ env('QINIU_DOMAIN') }}{{ \Session::get('userInfo')->avatar }}  @endif" id="images" alt=""/>
        <input type="hidden" value="{{ csrf_token() }}" id="token">
    </div>

    <p class="am-form-help">头像</p>

    <div class="info-m">
        <div><b>用户名：<i>{{ \Session::get('userInfo')->nickname }}</i></b></div>
        <div><b>登录账号：<i>{{ \Session::get('user')->login_name }}</i></b></div>
        <div class="u-safety">
            <a href="javascript:;">
                账户安全:
                <span class="u-profile">
                    <i class="bc_ee0000" style="width: 60px;" width="0">
                        @inject('calculated', 'App\Presenters\personalPresenter')
                        {{ $calculated->calculated() }}
                    </i>
                </span>
            </a>
        </div>
    </div>
</div>