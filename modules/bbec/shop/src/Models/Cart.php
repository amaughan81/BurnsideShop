<?php

namespace bbec\Shop\Models;

use Illuminate\Support\Facades\Session;

class Cart {

    public $items = null;
    public $totalQty = 0;
    public $totalPrice = 0;
    public $coupons = [];

    protected $name = "bbec_cart";

    public function __construct($cart)
    {
        if ($cart) {
            $this->items = $cart->items;
            $this->totalQty = $cart->totalQty;
            $this->totalPrice = $cart->totalPrice;
            $this->coupons = $cart->coupons;
        }
    }

    /**
     * Get the cart name
     *
     * @return string
     */
    public function getCart()
    {
        return $this->name;
    }

    /**
     * Add Product to cart
     *
     * @param Product $product
     */
    public function add(Product $product)
    {
        $storedItem = ['qty' => 0, 'price' => $product->price, 'item' => $product];

        if($this->has($product)) {
            $storedItem = $this->getItem($product);
        }

        $storedItem['qty']++;
        $storedItem['price'] = $product->price * $storedItem['qty'];
        $this->items[$product->id] = $storedItem;
        $this->totalQty++;
        $this->totalPrice += $product->price;

        $this->store();
    }

    /**
     * Remove a product from the cart
     *
     * @param Product $product
     */
    public function removeItem(Product $product)
    {
        $item = $this->items[$product->id];

        $this->totalQty -= $item['qty'];
        $this->totalPrice -= $item['price'];

        unset($this->items[$product->id]);

        if(count($this->items) > 0) {
            $this->store();
        } else {
            $this->destroy();
        }
    }

    public function setQuantity(Product $product, $quantity=1)
    {
        $quantity = (int)$quantity;

        if($this->has($product)) {
            $item = $this->getItem($product);
        }

        $originalQty = $item['qty'];
        $this->totalQty -= $originalQty;
        $item['qty'] = $quantity;

        $originalPrice = $item['price'];
        $this->totalPrice -= $originalPrice;
        $item['price'] = ($product->price * $quantity);

        $this->items[$product->id] = $item;

        if($quantity > 0) {
            $this->totalQty += $quantity;
            $this->totalPrice += $item['price'];
        }

        if($this->totalQty <= 0) {
            $this->destroy();
        } else {
            $this->store();
        }
    }

    /**
     * @param Product $product
     * @param int $quantity
     */
    public function reduceQuantity(Product $product, $quantity=1)
    {
        $quantity = (int)$quantity;

        // Set quantity to zero
        if($quantity == 0) {
            $quantity = 1;
        }

        $item = $this->items[$product->id];

        $item['qty'] -= $quantity;
        $item['price'] -= ($product->price * $item['qty']);

        $this->totalQty -= $quantity;
        $this->totalPrice -= $item['price'];

        if($this->totalQty <= 0) {
            $this->destroy();
        } else {
            $this->store();
        }
    }

    /**
     * Empty the shopping cart
     */
    public function empty()
    {
        $this->destroy();
    }

    /**
     * Apply a coupon to the cart
     *
     * @param Collection $coupon
     */
    public function applyCoupon($coupon)
    {
        if(!array_key_exists($coupon->code, $this->coupons)) {
            $this->coupons[$coupon->code] = $coupon->value;
            $this->store();
            return true;
        }
        return false;
    }

    /**
     * Remove a coupon from the cart
     *
     * @param $code
     * @return bool
     */
    public function removeCoupon($code)
    {
        if(array_key_exists($code, $this->coupons)) {
            unset($this->coupons[$code]);
            $this->store();

            return true;
        }
        return false;
    }

    /**
     * Get all items in the cart
     *
     * @return null
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Store the cart in Session data
     */
    private function store()
    {
        Session::put($this->getCart(), $this);
    }

    /**
     * Destroy the cart
     */
    private function destroy()
    {
        Session::forget($this->getCart());
    }

    /**
     * Get an item from the cart
     *
     * @param Product $product
     * @return mixed
     */
    public function getItem(Product $product)
    {
        return $this->items[$product->id];
    }

    /**
     * Check if the cart has a product
     *
     * @param Product $product
     * @return bool
     */
    public function has(Product $product)
    {
        if ($this->items) {
            if (array_key_exists($product->id, $this->items)) {
                return true;
            }
        }
        return false;
    }

    public function discounts()
    {
        return array_sum($this->coupons);
    }

}