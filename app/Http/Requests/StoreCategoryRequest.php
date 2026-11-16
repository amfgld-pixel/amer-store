<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->canAccess('categories.manage') ?? false; }
    public function rules(): array
    {
        $id=$this->route('category')?->id;
        return [
            'parent_id'=>[
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->whereNull('parent_id'),
                Rule::notIn(array_filter([$id])),
            ],
            'name'=>['required','string','max:150'],
            'name_bn'=>['nullable','string','max:150'],
            'slug'=>['required','alpha_dash','max:160',Rule::unique('categories')->ignore($id)],
            'description'=>['nullable','string'],
            'description_bn'=>['nullable','string'],
            'sort_order'=>['required','integer','min:0'],
            'meta_title'=>['nullable','string','max:160'],
            'meta_description'=>['nullable','string','max:500'],
            'is_active'=>['required','boolean'],
        ];
    }

    public function after(): array
    {
        return [function ($validator) {
            $category = $this->route('category');

            if ($category && $this->filled('parent_id') && $category->children()->exists()) {
                $validator->errors()->add('parent_id', 'A category that already has subcategories cannot become a subcategory.');
            }
        }];
    }
}
