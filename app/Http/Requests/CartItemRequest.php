<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class CartItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Handled by auth middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $product = Product::find($value); // Automatically excludes soft deleted products
                    if (!$product) {
                        $fail('Produk tidak ditemukan atau sudah tidak aktif.');
                        return;
                    }
                    if (!$product->is_active) {
                        $fail('Produk yang dipilih sedang tidak aktif.');
                    }
                },
            ],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Produk wajib dipilih.',
            'product_id.integer' => 'ID Produk tidak valid.',
            'quantity.required' => 'Jumlah barang wajib diisi.',
            'quantity.integer' => 'Jumlah barang harus berupa angka.',
            'quantity.min' => 'Jumlah barang minimal 1.',
        ];
    }
}
