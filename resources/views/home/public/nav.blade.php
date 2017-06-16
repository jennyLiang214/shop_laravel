<!--悬浮搜索框-->
<div class="nav white">
    <div class="logoBig">
        @inject('BasicConfig', 'App\Presenters\BasicConfigPresenter')
        <li><a href="/home/index"><img src="@if(empty($BasicConfig->getBasicConfig()->logo))/images/logobig.png @else {{ env('QINIU_DOMAIN') }}{{ $BasicConfig->getBasicConfig()->logo }}?imageView2/1/w/200/h/90 @endif"/></a></li>
    </div>
    <div class="search-bar pr">
        <a name="index_none_header_sysc" href="#"></a>
        <form action="/home/search">
            <input id="searchInput" name="keyword" value="{{ isset($_GET['keyword']) ? $_GET['keyword'] : '' }}" type="text" placeholder="搜索" autocomplete="off">
            <input id="ai-topsearch" class="submit am-btn" value="搜 索" index="1" type="submit">
        </form>
    </div>
</div>
<div class="clear"></div>