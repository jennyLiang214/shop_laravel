<!--顶部导航条 -->
<div class="am-container header">
    <ul class="message-l">
        <div class="topMessage">
            @if(empty(\Session::get('user')))
            <div class="menu-hd">
                <a href="/home/login" target="_top" class="h">亲，请登录</a>
                <a href="/home/register" target="_top">免费注册</a>
            </div>
            @else
                <div class="menu-hd">
                   欢迎光临，laraMall！
                </div>
            @endif
        </div>
    </ul>
    <ul class="message-r">
        <div class="topMessage home">
            <div class="menu-hd"><a href="/home/index" target="_top" class="h">商城首页</a></div>
        </div>
        <div class="topMessage my-shangcheng">
            <div class="menu-hd MyShangcheng"><a href="/home/personal" target="_top"><i class="am-icon-user am-icon-fw"></i>个人中心</a>
            </div>
        </div>
        <div class="topMessage mini-cart">
            <div class="menu-hd"><a id="mc-menu-hd" href="{{ url('/home/shoppingCart') }}" target="_top"><i
                            class="am-icon-shopping-cart  am-icon-fw"></i><span>购物车</span>
                    @inject('shopping', 'App\Presenters\ShoppingCartPresenter')
                    <strong id="J_MiniCartNum" class="h">{{ $shopping->shoppingCount() }}</strong>
                </a></div>
        </div>
        <div class="topMessage favorite">
            <div class="menu-hd">
                <a href="{{ url('/home/GoodsCollection') }}" target="_top"><i class="am-icon-heart am-icon-fw"></i><span>收藏夹</span></a>
            </div>
        </div>
        @if(!empty(\Session::get('user')))
            <div class="topMessage favorite">
                <div class="menu-hd">
                    <a href="{{ url('/home/logout') }}" target="_top"><span>退出</span></a>
                </div>
            </div>
         @endif
    </ul>
</div>