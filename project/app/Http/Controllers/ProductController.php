<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Cart;
use Session;

class ProductController extends Controller
{
    public function getIndex()
    {
      $products = Product::all();
      return view('shop.index',[
        'products' => $products
      ]);
    }

     //method to get product to user cart
    public function getAddToCart(Request $request, $id)
    {
          //first we find product
         $product = Product::find($id);
         //if user already add value we show it
         $oldCart = Session::has('cart') ? Session::get('cart') : NULL;
         //if not we make new cart
         $cart = new Cart($oldCart);
         //and add product woth its id
         $cart->add($product,$product->id);
         //and then put that on $cart
         $request->session()->put('cart',$cart);
         return redirect()->route('product.index');
    }

    //method to go to cart page and see purchase
    public function getCart()
    {
      //if user has no purchase we return
       if (!Session::has('cart')) {
           return view('shop.shopping-cart');
       }

       //else we get session of cart
       $oldCart = Session::get('cart');
       //and again create new cart
       $cart = new Cart($oldCart);
       //and get resutl of purchase to our page [Items & Price]
       return view('shop.shopping-cart',[
         'products' => $cart->items,
         'totalPrice' => $cart->totalPrice
       ]);
    }

    public function getCheckhOut()
    {
      //if user has no purchase we return
       if (!Session::has('cart')) {
           return view('shop.shopping-cart');
       }
       $oldCart = Session::get('cart');
       $cart = new Cart($oldCart);
       $total = $cart->totalPrice;
       return view('shop.checkout',[
         'total' => $total
       ]);

    }
}
