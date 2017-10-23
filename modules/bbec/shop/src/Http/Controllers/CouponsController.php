<?php

namespace bbec\Shop\Http\Controllers;

use App\Acl;
use bbec\Shop\Models\Coupon;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    public function __construct()
    {
        Acl::init();
        Acl::deny('guest');
        Acl::deny('student');
        Acl::deny('staff');
        Acl::deny('manager');
        Acl::deny('ls_admin');
        Acl::deny('priv_user');
        Acl::deny('slt');
        Acl::deny('admin');
        Acl::allow('super_admin');

        $this->middleware('amacl');
    }

    public function index()
    {
        $coupons = Coupon::unclaimed()->get();
        return view('shop::coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('shop::coupons.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $coupon = new Coupon();
        $coupon->massCreate($data['value'], $data['quantity']);

        flash('Success - Coupons have been created')->success()->important();

        return redirect('/coupons');

    }

    /**
     * Convert the page to a PDF use DOMPDF
     *
     * @param Request $request
     * @return mixed
     */
    public function pdf(Request $request)
    {
        $coupons = Coupon::unclaimed()->get();
        view()->share('coupons', $coupons);

        $pdf = \PDF::loadView('shop::coupons.pdf');
        return $pdf->stream('invoice.pdf');

        //return view('shop::coupons.pdf', compact('coupons'));

    }

    /**
     * Delete a coupon
     *
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        $json = ['result' => false];
        if(Coupon::destroy($id)) {
            $json['result'] = true;
        }
        return $json;
    }
}
