<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuctionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:Category,category_id',
            'starting_price' => 'required|numeric|min:0',
            'reserve_price' => 'required|numeric|min:0',
            'minimum_bid_increment' => 'required|numeric|min:0',
            'description' => 'required|string',
            'starting_date' => 'required|date|after_or_equal:today',
            'ending_date' => 'required|date|after:starting_date',
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status' => 'required|in:Active,Closed',
        ];
    }
}
