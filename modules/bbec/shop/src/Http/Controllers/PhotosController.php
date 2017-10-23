<?php

namespace bbec\Shop\Http\Controllers;

use App\Acl;
use App\Http\Controllers\Controller;
use bbec\Shop\Http\Requests\AddPhotoRequest;
use bbec\Shop\Models\AddPhotoToProduct;
use bbec\Shop\Models\Product;
use bbec\Shop\Models\ProductPhoto;
use Symfony\Component\HttpFoundation\Request;

class PhotosController extends Controller
{
    /**
     * @param $slug
     * @param AddPhotoRequest $request
     */
    public function store($slug, Request $request)
    {
        $product = Product::slug($slug);
        $photo = $request->file('photo');

        $lastPhoto = (new AddPhotoToProduct($product, $photo))->save();

        //$lastPhoto = $product->photos()->get()->last();
        return $lastPhoto->toArray();
    }

    /**
     * Delete the photo in DB and filesystem
     *
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        $json = ['result' => false];
        $photo = ProductPhoto::findOrFail($id);
        if($photo->delete()) {
            $json['result'] = true;
        }
        return $json;
    }
}
