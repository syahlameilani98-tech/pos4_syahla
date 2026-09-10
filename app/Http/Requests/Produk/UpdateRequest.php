<?php

namespace App\Http\Requests\Produk;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'jenis_id' => 'required|exists:jenis,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'required|string|max:255',
            'purchase_price' => 'required|integer|min:0',
            'selling_price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_id.required' => 'Jenis wajib dipilih.',
            'jenis_id.exists' => 'Jenis yang dipilih tidak valid.',
            'foto.image' => 'File yang di upload harus gambar.',
            'foto.mimes' => 'Extensi gambar harus JPG, JPEG, PNG.',
            'foto.max' => 'Maksimal ukuran gambar 2MB.',
            'name.required' => 'Nama wajib diisi.',
            'purchase_price.required' => 'Purchase price wajib diisi.',
            'purchase_price.integer' => 'Purchase price harus diisi bilangan bulat.',
            'selling_price.required' => 'Selling price wajib diisi.',
            'selling_price.integer' => 'Selling price harus diisi bilangan bulat.',
            'stock.required' => 'Stock wajib diisi.',
            'stock.integer' => 'Stock harus diisi angka.',
        ];
    }
}