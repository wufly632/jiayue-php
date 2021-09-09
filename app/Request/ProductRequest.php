<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class ProductRequest extends FormRequest
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
            'title' => 'required',
            'styleId' => 'required',
            'typeId' => 'required',
            'productModel' => 'required',
            'pictures' => 'required|array',
//            'detailPictures' => 'required|array',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'title is required',
            'styleId.required' => 'styleId is required',
            'typeId.required' => 'typeId is required',
            'productModel.required' => 'productModel is required',
            'pictures.required' => 'pictures is required',
            'detailPictures.required' => 'styleId is required',
        ];
    }
}
