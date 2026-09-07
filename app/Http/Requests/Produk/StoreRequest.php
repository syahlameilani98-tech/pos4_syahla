<?php

namespace App\Http\Requests\Produk;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jenis_id'       => 'required|exists:jenis,id',
            'foto'           => 'nullable|image|mimes:jpg,png|max:2048',
            'name'           => 'required|string|max:255',
            'purchase_price' => 'required|integer|min:0',
            'selling_price'  => 'required|integer|min:0',
            'stock'          => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_id.required' => 'Jenis produk wajib dipilih.',
            'jenis_id.exists'   => 'Jenis produk yang dipilih tidak valid.',
            'foto.image'  => 'File yang diupload harus gambar.',
            'foto.mimes'  => 'Extensi gambar harus JPG, JPEG, PNG.',
            'foto.max'    => 'Maksimal ukuran gambar 2mb.',
            'name.required' => 'Nama wajib diisi.',
            'purchase_price.required' => 'Purchase price wajib diisi.',
            'purchase_price.integer' => 'Purchase price harus diisi bilangan bulat.',
            'selling_price.required' => 'Selling price wajib diisi.',
            'selling_price.integer' => 'Selling price harus diisi bilangan bulat.',
            'stock.integer' => 'Stock harus diisi angka.',
        ];
    }
}