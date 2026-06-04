<?php

namespace App\Http\Requests\Api\V1\Products;

use Illuminate\Foundation\Http\FormRequest;

class SetPrimaryProductImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $product = $this->route('product');
        $image = $this->route('image');

        return $product !== null
            && $image !== null
            && $image->product_id === $product->id
            && ($this->user()?->can('update', $product) ?? false);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }
}
