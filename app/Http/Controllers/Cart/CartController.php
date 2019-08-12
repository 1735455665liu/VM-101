<?php

namespace App\Http\Controllers\Cart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Goodsmodel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class CartController extends Controller
{
    //商品展示
    public  function goodslist()
    {

        $goodsList= DB::table('shop_goods')->limit(5)->get();
        $where=[
          'goods'=>$goodsList
        ];
        return view('goods/goods',$where);
    }
    //加入购物车
    public function cartadd(Request $request)
    {
        //用户id
        $user_id=Auth::id();
        $goods_id=$request->input('goods_id');
        $where=[
          'user_id'=>$user_id,
            'goods_id'=>$goods_id
        ];
        $goods_where=[
            'goods_id'=>$goods_id,
            'is_sold'=>1 //1是未下架   2是已下架
        ];
        if(!$user_id)
        {
            $response=[
              'error'=>0,
              'msg'=>'请先登录'
            ];
            return json_encode($response,JSON_UNESCAPED_UNICODE);

        }
        //判断商品是否下架
        $goodsInfo=DB::table('shop_goods')->where($goods_where)->first();
        if(!$goodsInfo) //商品已下架
        {
            $response=[
                'error'=>0,
                'msg'=>'此商品已下架'
            ];
            return json_encode($response,JSON_UNESCAPED_UNICODE);

        }else{ //商品未下架

            $cartUser=DB::table('shop_care')->where($where)->first();
            if($cartUser)
            {
                $update_where=[
                    'buy_number'=>$cartUser->buy_number+1
                ];
                $where=[
                    'user_id'=>$user_id,
                    'goods_id'=>$goods_id,
                    'cart_status'=>1
                ];

                //判断是否有该商品   如果有修改库存
                $GoodsInfo=DB::table('shop_care')->where($where)->update($update_where);
                if($GoodsInfo)
                {
                    $response=[
                        'error'=>0,
                        'msg'=>'加入购物车成功'
                    ];
                    return json_encode($response,JSON_UNESCAPED_UNICODE);
                }else{
                    $response=[
                        'error'=>1,
                        'msg'=>'加入购物车失败'
                    ];
                    return json_encode($response,JSON_UNESCAPED_UNICODE);
                }


            }else{
                //没有则入库
                $GoodsInfo=DB::table('shop_goods')->where(['goods_id'=>$goods_id])->first();
                $where=[
                    'goods_id'=>$GoodsInfo->goods_id,
                    'goods_name'=>$GoodsInfo->goods_name,
                    'self_price'=>$GoodsInfo->self_price,
                    'buy_number'=>1,
                    'user_id'=>Auth::id(),
                    'create_time'=>time(),
                    'update_time'=>time(),
                    'cart_status'=>1,
                ];
                $cartInfo=DB::table('shop_care')->insertGetId($where);
                if($cartInfo){
                    $response=[
                        'error'=>0,
                        'msg'=>'加入购物车成功'
                    ];
                    return json_encode($response,JSON_UNESCAPED_UNICODE);
                }else{
                    $response=[
                        'error'=>1,
                        'msg'=>'加入购物车失败'
                    ];
                    return json_encode($response,JSON_UNESCAPED_UNICODE);
                }
            }

        }



    }
    //购物车列表
    public function cartlist()
    {
        //获取购物车列表的数据
        $cartlist=DB::table('shop_care')->where(['cart_status'=>1])->get();
        $where=[
            'cart'=>$cartlist,

        ];
        return view('cart/cartlist',$where);
        
        
    }
    
    
}
