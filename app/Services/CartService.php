<?php
namespace App\Services;
use App\Models\ProductVariant;
use Illuminate\Contracts\Session\Session;
use Illuminate\Validation\ValidationException;
class CartService {
 public function __construct(private Session $session){}
 public function items():array {return array_values($this->session->get('amer_cart',[]));}
 public function add(ProductVariant $variant,int $quantity):void {$variant->load('product.images');abort_unless($variant->is_active&&$variant->product->status==='active',404);$cart=$this->session->get('amer_cart',[]);$key=(string)$variant->id;$existing=$cart[$key]['quantity']??0;$cart[$key]=['variant_id'=>$variant->id,'product_id'=>$variant->product_id,'name'=>$variant->product->name,'slug'=>$variant->product->slug,'variant'=>$variant->name,'sku'=>$variant->sku,'price'=>(float)$variant->price,'quantity'=>min(99,$existing+$quantity),'image'=>$variant->product->images->first()?->path];$this->session->put('amer_cart',$cart);}
 public function update(ProductVariant $variant,int $quantity):void {$cart=$this->session->get('amer_cart',[]);if(!isset($cart[(string)$variant->id]))throw ValidationException::withMessages(['cart'=>'Item is not in cart.']);if($quantity<1)unset($cart[(string)$variant->id]);else $cart[(string)$variant->id]['quantity']=min(99,$quantity);$this->session->put('amer_cart',$cart);}
 public function remove(ProductVariant $variant):void {$cart=$this->session->get('amer_cart',[]);unset($cart[(string)$variant->id]);$this->session->put('amer_cart',$cart);}
 public function clear():void {$this->session->forget('amer_cart');}
 public function subtotal():float {return array_reduce($this->items(),fn($sum,$i)=>$sum+($i['price']*$i['quantity']),0.0);}
 public function count():int {return array_sum(array_column($this->items(),'quantity'));}
}
