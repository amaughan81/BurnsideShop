<?php

namespace bbec\Shop\Http\Controllers;

use App\Acl;
use bbec\Shop\Models\Cart;
use bbec\Shop\Models\Coupon;
use bbec\Shop\Models\CouponOrder;
use bbec\Shop\Models\Order;
use bbec\Shop\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OrdersController extends Controller
{
    public function __construct()
    {
        Acl::init();
        Acl::deny('guest');
        Acl::allow('student', ['index', 'show', 'store', 'complete']);
        Acl::allow('staff');
        Acl::allow('manager');
        Acl::allow('ls_admin');
        Acl::allow('priv_user');
        Acl::allow('slt');
        Acl::allow('admin');
        Acl::allow('super_admin');

        $this->middleware('amacl');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status = 0)
    {
        if(auth()->user()->role == "student") {
            $order = auth()->user()->orders();
        } else {
            $order = Order::status($status);
        }
        $orders  = $order->orderBy('id', 'DESC')->get();
        return view('shop::orders.index', compact('orders','status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $existingCart = Session::has('bbec_cart') ? Session::get('bbec_cart') : null;

        $cart = new Cart($existingCart);

        // Create a new order
        $order = new Order();
        if($request->has('user_id')) {
            $order->user_id = $request->user_id;
        } else {
            $order->user_id = \Auth::user()->id;
        }
        $order->amount = ($cart->totalPrice - $cart->discounts());
        $order->save();

        // Add cart items to the order
        foreach($cart->items as $item) {

            $op = new OrderProduct();
            $op->add($order->id, $item['item']->id, $item['qty']);
            // Reduce stock level
            $item['item']->quantity( ($item['qty'] * -1) );
        }

        $coupon = new Coupon();
        //$couponOrder = new CouponOrder();
        foreach($cart->coupons as $code=>$value) {

            $order->coupons()->create(
                ['code' => $code, 'order_id'=>$order->id]
            );

            // redeem coupons
            $coupon->redeem($code);
            // add coupon to order
            //$couponOrder->add($code, $order->id);
        }

        // TODO: Need to trigger email to user and store admin

        // Destroy the cart
        $cart->empty();

        flash('Success - Your order has been placed and the store admin notified')->success()->important();

        return redirect('/orders/complete/'.$order->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('shop::orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $order->update(['status'=>1]);
        flash('Success - Order #'.$order->id.' has been completed.')->success()->important();
        return redirect('/orders');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $json = ['result' => false];

        $order = Order::findOrFail($id);

        // First get all order products and adjust product quantities
        foreach($order->orderProducts as $orderProduct) {
            $orderProduct->product->quantity += $orderProduct->quantity;
            $orderProduct->product->save();
        }

        // Get all coupons and refund them, then delete
        foreach($order->coupons as $orderCoupon) {
            $orderCoupon->coupon->redeemed = 0;
            $orderCoupon->coupon->save();
        }

        if(Order::destroy($id)) {
            $json['result'] = true;
        }
        return $json;
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function complete($id)
    {
        return view('shop::orders.complete', compact('id'));
    }
}
