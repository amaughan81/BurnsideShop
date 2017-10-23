<?php

namespace bbec\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'value', 'redeemed'];
    //
    public function massCreate($value,$total)
    {
        for($i=0;$i<$total;$i++) {
            self::create([
                'code' => $this->generateCode(),
                'value' => $value
            ]);
        }
    }

    public function generateCode()
    {
        do {
            $code = $this->getToken(10, time());
            $coupon_code = Coupon::where('code',$code)->get();
        }
        while($coupon_code->isNotEmpty());

        return $code;
    }

    public function getToken($length, $seed){
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "0123456789";

        mt_srand($seed);      // Call once. Good since $application_id is unique.

        for($i=0;$i<$length;$i++){
            $token .= $codeAlphabet[mt_rand(0,strlen($codeAlphabet)-1)];
        }
        return $token;
    }

    public function hasBeenRedeemed($code)
    {
        return (boolean) self::where([
            ['code', $code],
            ['redeemed',1]
        ])->count();
    }

    /**
     * @param $query
     * @param $code
     * @return mixed
     */
    public function scopeCode($query, $code)
    {
        return $query->where('code', $code);
    }

    /**
     * A coupon can be redeemed
     *
     * @param $code
     */
    public function redeem($code)
    {
        $this->where('code', $code)->update(['redeemed' => 1]);
    }

    /**
     * Return all unclaimed vouchers
     *
     * @return mixed
     */
    public static function scopeUnclaimed($query)
    {
        return $query->where('redeemed', 0);
    }
}
