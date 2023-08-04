<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'nullable|min:3|max:255',
            'email' => 'nullable|email|min:3|max:255',
            'mobile' => 'nullable|ir_mobile',
            'amount' => 'required|numeric|min_digits:5',
            'driver' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min_digits' => 'حداقل مبلغ واریزی 10,000 ریال می باشد.',
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name' => 'نام',
            'last_name' => 'نام خانوادگی',
            'email' => 'ایمیل',
            'mobile' => 'موبایل',
            'amount' => 'مبلغ',
            'driver' => 'درگاه',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'amount' => str_replace(',', '', $this->amount),
        ]);
    }

}
