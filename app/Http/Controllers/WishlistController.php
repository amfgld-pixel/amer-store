<?php
namespace App\Http\Controllers;
use App\Models\Product;use App\Models\Wishlist;use Illuminate\Http\Request;
class WishlistController extends Controller {public function index(Request $r){return view('storefront.wishlist',['products'=>Product::with(['brand','images','variants'])->whereHas('wishlists',fn($q)=>$q->where('user_id',$r->user()->id))->get()]);}public function toggle(Request $r,Product $product){$item=Wishlist::where('user_id',$r->user()->id)->where('product_id',$product->id)->first();if($item)$item->delete();else Wishlist::create(['user_id'=>$r->user()->id,'product_id'=>$product->id]);return back()->with('status',$item?'Removed from wishlist.':'Saved to wishlist.');}}
