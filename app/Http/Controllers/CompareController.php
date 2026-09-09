<?php
namespace App\Http\Controllers;
use App\Models\Product;use Illuminate\Http\Request;
class CompareController extends Controller {public function index(Request $r){$ids=$r->session()->get('amer_compare',[]);return view('storefront.compare',['products'=>Product::with(['brand','images','variants','categories'])->whereIn('id',$ids)->get()]);}public function toggle(Request $r,Product $product){$ids=$r->session()->get('amer_compare',[]);if(in_array($product->id,$ids))$ids=array_values(array_diff($ids,[$product->id]));elseif(count($ids)<4)$ids[]=$product->id;$r->session()->put('amer_compare',$ids);return back()->with('status','Comparison updated.');}}
