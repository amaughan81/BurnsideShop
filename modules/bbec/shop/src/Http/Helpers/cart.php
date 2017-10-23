<?php

function getBasketCount() {
    $existingCart = Session::has('bbec_cart') ? Session::get('bbec_cart') : null;
    $cart = new bbec\Shop\Models\Cart($existingCart);
    return $cart->totalQty;
}