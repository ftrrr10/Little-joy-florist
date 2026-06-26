<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_phone' => ['required', 'string', 'min:10', 'max:15'],
            'delivery_address' => ['required', 'string'],
            'delivery_date' => ['required', 'date', 'after_or_equal:today'],
            'greeting_message' => ['nullable', 'string', 'max:500'],
            'customer_note' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'recipient_name.max' => 'Nama penerima terlalu panjang.',
            'recipient_phone.required' => 'Nomor telepon penerima wajib diisi.',
            'recipient_phone.min' => 'Nomor telepon penerima minimal 10 digit.',
            'recipient_phone.max' => 'Nomor telepon penerima maksimal 15 digit.',
            'delivery_address.required' => 'Alamat pengiriman wajib diisi.',
            'delivery_date.required' => 'Tanggal pengiriman wajib ditentukan.',
            'delivery_date.date' => 'Format tanggal pengiriman tidak valid.',
            'delivery_date.after_or_equal' => 'Tanggal pengiriman tidak boleh di masa lalu.',
            'greeting_message.max' => 'Kartu ucapan maksimal 500 karakter.',
            'customer_note.max' => 'Catatan pesanan maksimal 500 karakter.',
        ];
    }
}
