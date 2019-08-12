<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
    //生成订单
    public function orderadd(Request $request)
    {
        //接受商品id
        $goods_id=$request->input('goods_id');
        //去除,
        $g_id=explode(',',$goods_id); //一维数组
        //加入商品详情表
        $cart_status=[
            'cart_status'=>1
        ];
        $Detail=DB::table('shop_care')->whereIn('goods_id',$g_id)->get();
      //获取商品总价格
        $price_con=0;//总价格
        foreach ($Detail as $k=>$v)
        {
            $price_con+=$v->self_price*$v->buy_number;
        }
        $oreder_no=time().rand(1111,9999);
        $where=[
            'order_no'=>$oreder_no,
            'user_id'=>Auth::id(),
            'order_amout'=>$price_con,
            'create_time'=>time(),
            'update_time'=>time()
        ];

        //加入订单表
        $OrderInfo=DB::table('shop_order')->insertGetId($where);


        $whereInfo=[
            'is_sold'=>1,//未下架
            'cart_status'=>1//未删除

        ];
        $cart_goods=DB::table("shop_care")
            ->join("shop_goods","shop_goods.goods_id","=","shop_care.goods_id")
            ->where(["shop_care.cart_status"=>1])
            ->get();
        //获取订单id
        $res=DB::table('shop_order')->where('order_no',$oreder_no)->get(['order_id']);
        $order_id=$res[0]->order_id;
        //循环数据入库
        foreach ($cart_goods as $k=>$v)
        {
            $where=[
              'order_id'=>$order_id,
                'user_id'=>Auth::id(),
                'goods_id'=>$v->goods_id,
                'goods_price'=>$v->self_price,
                'buy_number'=>$v->buy_number,
                'goods_name'=>$v->goods_name,
                'create_time'=>time(),
                'goods_num'=>$v->goods_num,
                'goods_img'=>$v->goods_img
            ];
          $insert=DB::table('shop_detail')->insert($where);
        }
        $updateInfo =DB::table('shop_care')->update(['cart_status'=>2]); //生成订单 修改购物车状态
        if($updateInfo)
        {
            $response=[
                'error'=>0,
                'msg'=>'生成订单成功'
            ];
            return json_encode($response,JSON_UNESCAPED_UNICODE);
        }else{
            $response=[
                'error'=>0,
                'msg'=>'生成订单失败'
            ];
            return json_encode($response,JSON_UNESCAPED_UNICODE);

        }



    }
    //订单列表
    public function orderlist(Request $request)
    {
        $OrderInfo=DB::table('shop_order')->get();
        $data=[
            'order'=>$OrderInfo
        ];
    return view("order.orderlist",$data);

    }
}
