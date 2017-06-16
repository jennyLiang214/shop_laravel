<div class="footer">
    <div class="footer-hd">
        @inject('Links','App\Presenters\HomeLinksPresenter')
        @foreach( $Links -> getLinks() as $link)
            {{--显示友情链接名称--}}
            {{--<a href="{{$link->url}}" target="_blank"><font size="4" color="#a52a2a">{{ $link->name }}</font></a>--}}
            {{--显示友情链接图片--}}
            @if (($link->type) == 1)
                <a href="{{$link->url}}" target="_blank"><img src="{{ env("QINIU_DOMAIN") }}{{ $link->image }}" style="width:45px" height="35px"></a>
            @else
                <a href="{{$link->url}}" target="_blank"><font size="4" color="red">{{ $link->name }}</font></a>
            @endif
            <b>|</b>
        @endforeach
        <hr style="height:1px;border:none;border-top:1px solid ;" />
    </div>
    <div class="footer-bd">
        @inject('BasicConfig', 'App\Presenters\BasicConfigPresenter')
        <p style="text-align:center">
            <em>@if(!empty($BasicConfig->getBasicConfig()->copyright)) {{$BasicConfig->getBasicConfig()->copyright}} @endif @if(!empty($BasicConfig->getBasicConfig()->record_number)) {{ $BasicConfig->getBasicConfig()->record_number }} @endif</em>
        </p>
    </div>
</div>


