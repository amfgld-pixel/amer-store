<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->canAccess('settings.update') ?? false; }
    public function rules(): array
    {
        return [
            'name'=>['required','string','max:150'],'name_bn'=>['nullable','string','max:150'],
            'email'=>['nullable','email','max:150'],'phone'=>['nullable','string','max:30'],
            'whatsapp'=>['nullable','string','max:30'],'facebook_url'=>['nullable','url','max:255'],
            'address'=>['nullable','string','max:2000'],'address_bn'=>['nullable','string','max:2000'],
            'country_code'=>['required','string','size:2'],'currency_code'=>['required','string','size:3'],
            'timezone'=>['required','timezone'],'locale'=>['required','in:en,bn'],'is_active'=>['required','boolean'],
        ];
    }
}
