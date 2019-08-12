
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script src="/js/jquery.min.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>商品列表</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        </div>
    @endif
        <table border=0>
            <tr>
                <td>商品id</td>
                <td>商品名称</td>
                <td>商品价格</td>
                <td>商品库存</td>
            </tr>

            @foreach($goods as $k=>$v)
                <br>
                <tr>

                    <td>{{$v->goods_id}}</td>
                    <td>{{$v->goods_name}}</td>
                    <td>{{$v->self_price}}</td>
                    <td>{{$v->goods_num}}</td>
                    <td goods_id="{{$v->goods_id}}"><button class="cart">加入购物车</button></td>
                </tr>
            @endforeach
        </table>

    <div class="content">
        <div class="title m-b-md">
        </div>

    </div>
</div>
</body>
</html>
<script>
    $(function()
    {
        //点击获取商品id加入购物车
        $('.cart').click(function () {
           var _this=$(this);
           var goods_id=_this.parents().attr("goods_id");
            $.post(
                '/cart/add',
                {goods_id:goods_id},
                function(res)
                {
                    alert(res.msg);
                },'json'
           )

        })
    })




</script>
