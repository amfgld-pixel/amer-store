<?php
namespace App\Services;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
class CatalogService
{
    public function saveCategory(array $data, ?Category $category=null): Category
    {
        return DB::transaction(function () use ($data,$category) {
            $old=$category?->toArray(); $category ??= new Category();
            $category->fill($data)->save(); $this->audit($old?'updated':'created',$category,$old,$category->fresh()->toArray());
            return $category->fresh();
        });
    }
    public function saveProduct(array $data, ?Product $product=null): Product
    {
        return DB::transaction(function () use ($data,$product) {
            $old=$product?->load('variants','categories')->toArray(); $product ??= new Product();
            $product->fill(Arr::except($data,['category_ids','variants']))->save();
            $product->categories()->sync($data['category_ids']);
            $kept=[];
            foreach ($data['variants'] as $index=>$variantData) {
                $variant=$product->variants()->updateOrCreate(['sku'=>$variantData['sku']],$variantData+['sort_order'=>$index]);
                $kept[]=$variant->id;
            }
            $product->variants()->whereNotIn('id',$kept)->delete();
            $fresh=$product->fresh(['variants','categories']); $this->audit($old?'updated':'created',$fresh,$old,$fresh->toArray());
            return $fresh;
        });
    }
    private function audit(string $event, object $model, ?array $old, array $new): void
    {
        AuditLog::create(['user_id'=>auth()->id(),'event'=>$event,'auditable_type'=>$model::class,'auditable_id'=>$model->id,'old_values'=>$old,'new_values'=>$new,'ip_address'=>request()->ip(),'user_agent'=>request()->userAgent(),'created_at'=>now()]);
    }
}
