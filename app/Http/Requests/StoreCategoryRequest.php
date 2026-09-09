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
        return ['parent_id'=>['nullable','exists:categories,id'],'name'=>['required','string','max:150'],'name_bn'=>['nullable','string','max:150'],'slug'=>['required','alpha_dash','max:160',Rule::unique('categories')->ignore($id)],'description'=>['nullable','string'],'description_bn'=>['nullable','string'],'sort_order'=>['required','integer','min:0'],'meta_title'=>['nullable','string','max:160'],'meta_description'=>['nullable','string','max:500'],'is_active'=>['required','boolean']];
    }
}
