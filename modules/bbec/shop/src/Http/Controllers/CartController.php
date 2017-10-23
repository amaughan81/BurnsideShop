<?php

namespace bbec\Shop\Http\Controllers;

use App\Acl;
use App\BSG;
use App\Http\Controllers\Controller;
use App\TermDates;
use App\User;
use bbec\Shop\Models\Coupon;
use bbec\Shop\Models\Merit;
use bbec\Shop\Models\Product;
use bbec\Shop\Models\Cart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class CartController extends Controller
{
    public function __construct()
    {
        Acl::init();
        Acl::allow('guest', ['index', 'store', 'update', 'empty', 'destroy']);
        Acl::allow('student');
        Acl::allow('manager');
        Acl::allow('staff');
        Acl::allow('ls_admin');
        Acl::allow('priv_user');
        Acl::allow('slt');
        Acl::allow('admin');
        Acl::allow('super_admin');

        $this->middleware('amacl');
    }


    public function index()
    {
        $existingCart = Session::has('bbec_cart') ? Session::get('bbec_cart') : null;

        $cart = new Cart($existingCart);

        return view('shop::cart.index', ['title'=>'Shopping Cart', 'cart'=>$cart]);
    }

    public function store(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $existingCart = Session::has('bbec_cart') ? Session::get('bbec_cart') : null;

        $cart = new Cart($existingCart);
        $cart->add($product);

        flash('Success - Product has been added to your basket')->success();

        return redirect('/cart');
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $existingCart = Session::has('bbec_cart') ? Session::get('bbec_cart') : null;

        $cart = new Cart($existingCart);

        // first check if enough stock
        if($product->quantity >= $request->quantity) {

            $cart->setQuantity($product, $request->quantity);
            return ['result' => true];
        }
        else {
            $message = 'Quantity could not be updated.  There ';
            if($product->quantity == 1) {
                $message .= ' is only 1 item';
            } else {
                $message .= ' are only '.$product->quantity.' items';
            }
            $message .= ' left in stock';
            $item = $cart->getItem($product);
            return [
                'result' => false,
                'message' => $message,
                'originalQty' => $item['qty']
            ];
        }
    }

    /**
     * Show the checkout - displays the contents of the shopping cart
     *
     * @param int $user_id
     * @return mixed
     */
    public function checkout($user_id = 0)
    {
        $existingCart = Session::has('bbec_cart') ? Session::get('bbec_cart') : null;

        $cart = new Cart($existingCart);

        $viewVars = ['cart' => $cart, 'user_id' => $user_id, 'user' => auth()->user(), 'locked' => false];


        if(auth()->user()->role != "student") {

            if($user_id > 0) {
                $merit = new Merit();
                $merits = $merit->getCurrentMeritValue($user_id);

                $student = User::findOrFail($user_id);

                // If no merits are present enqueue the request to SIMS
                if(count($merits) == 0) {
                    $td = new TermDates();
                    $merits = $merit->syncMeritPoints($td->getFirstDate(), $td->getEndDate(), $student->sims_id, $student->sims_id);
                }
                $viewVars['user'] = $student;
            }

            $students = User::where('role','student')->orderby('surname', 'ASC')->get();
            $viewVars['students'] = $students;
        } else {
            // need to check if student has already made a purchase today
            $orders = auth()->user()->orders()->orderBy('created_at','DESC')->first();

            // Only allow students to make one purchase per day, to prevent spamming
            if($orders->count() > 0) {
                $today = Carbon::now()->format("Y-m-d");
                $lastOrderDate = Carbon::parse($orders->created_at)->format("Y-m-d");
                if($today == $lastOrderDate) {
                    $viewVars['locked'] = true;
                }
            }
        }

        return view('shop::cart.checkout', $viewVars);
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $existingCart = Session::has('bbec_cart') ? Session::get('bbec_cart') : null;

        $cart = new Cart($existingCart);
        $cart->removeItem($product);

        flash('Item has been removed from your basket')->success();

        return redirect('/cart');
    }

    /**
     * Empty the shopping cart
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function empty()
    {
        $existingCart = Session::has('bbec_cart') ? Session::get('bbec_cart') : null;

        $cart = new Cart($existingCart);
        $cart->empty();

        flash('Your basked has been cleared')->success();

        return redirect('/cart');
    }

    /**
     * Apply coupon to the shopping cart
     *
     * @param Request $request
     * @return array
     */
    public function applyCoupon(Request $request)
    {
        $json = [];

        $coupon = new Coupon();

        $existingCart = Session::has('bbec_cart') ? Session::get('bbec_cart') : null;

        $cart = new Cart($existingCart);

        // Make sure the coupon has not already been redeemed
        if(!$coupon->hasBeenRedeemed($request->code)) {

            // check the coupon is valid
            $validCoupon = Coupon::code($request->code)->first();

            if($validCoupon) {
                // if the coupon is valid apply it to the cart
                if($cart->applyCoupon($validCoupon)) {
                    $json['status'] = 'success';
                    $json['message'] = 'Success Coupon applied';
                    $json['code'] = $validCoupon->code;
                    $json['value'] = $validCoupon->value;
                    $json['discount'] = $cart->discounts();
                    $json['totalPrice'] = $cart->totalPrice;
                } else {
                    $json['status'] = 'error';
                    $json['message'] = 'Coupon has already been used.  Please try a different code';
                }
            } else {
                $json['status'] = 'error';
                $json['message'] = 'Coupon has does not exist';
            }
        } else {
            $json['status'] = 'error';
            $json['message'] = 'Coupon has already been used';
        }

        return $json;

    }

    /**
     * Remove the coupon from the checkout
     *
     * @param Request $request
     * @return array
     */
    public function removeCoupon(Request $request)
    {
        $json = ['result' => false];
        if($request->has('coupon')) {
            $existingCart = Session::has('bbec_cart') ? Session::get('bbec_cart') : null;
            $cart = new Cart($existingCart);
            $json['result'] = $cart->removeCoupon($request->coupon);
            $json['discount'] = $cart->discounts();
            $json['totalPrice'] = $cart->totalPrice;
        }
        return $json;
    }
}