
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script src="/js/jquery.min.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>购物车列表</title>

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

    @if(empty($cart))

        @else
            <table border=0>
                <tr>
                    <td>商品id</td>
                    <td>商品名称</td>
                    <td>商品价格</td>
                    <td>购买数量</td>
                </tr>

                @foreach($cart as $k=>$v)
                    <br>
                    <tr class="goods" goods_id="{{$v->goods_id}}">

                        <td >{{$v->goods_id}}</td>
                        <td>{{$v->goods_name}}</td>
                        <td>{{$v->self_price}}</td>
                        <td>{{$v->buy_number}}</td>
                    </tr>
                @endforeach
                <tr>
                    <button class="pay">立即下单</button>
                </tr>
            </table>
        @endif


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
        //立即下单 获取商品id
        $('.pay').click(function () {
            var goods=$('.goods');
            var goods_id='';
            //循环,号间隔商品id
            goods.each(function () {
                goods_id+=$(this).attr('goods_id')+',';
            })
            //去取尾部的,号
            goods_id=goods_id.substr(0,goods_id.length-1);
           $.post(
               "/order/orderadd",
               {goods_id:goods_id},
               function (res) {
                   alert(res.msg);
                    history.go(0);
               },'json'



           )

        })







    })




</script>
