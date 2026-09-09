<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierRequest;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index() { return view('admin.inventory.suppliers', ['suppliers'=>Supplier::latest()->paginate(30)]); }
    public function store(StoreSupplierRequest $request) { Supplier::create($request->validated()); return back()->with('status','Supplier created.'); }
    public function update(StoreSupplierRequest $request, Supplier $supplier) { $supplier->update($request->validated()); return back()->with('status','Supplier updated.'); }
}
