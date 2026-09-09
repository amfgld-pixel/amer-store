<?php
namespace App\Http\Controllers;use App\Models\NewsletterSubscriber;use Illuminate\Http\Request;
class NewsletterController extends Controller{public function store(Request $r){$v=$r->validate(['email'=>'required|email|max:190']);NewsletterSubscriber::updateOrCreate(['email'=>strtolower($v['email'])],['locale'=>app()->getLocale(),'status'=>'subscribed','source'=>'storefront','subscribed_at'=>now(),'unsubscribed_at'=>null]);return back()->with('status','Welcome to the AMER list.');}}
