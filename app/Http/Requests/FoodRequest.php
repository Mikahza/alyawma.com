<?php

namespace App\Http\Requests;

use App\Enums\FoodUnit;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FoodRequest extends FormRequest
{
    /**
     * Determine whether the user may make this request.
     *
     * Authorization belongs here rather than in the controller: a FormRequest
     * authorizes before it validates, so someone aiming at another account's
     * food gets a flat 403 instead of a map of the validation rules.
     */
    public function authorize(): bool
    {
        $food = $this->route('food');

        return $food === null || $this->user()->can('update', $food);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Grams are limited to a single decimal place on purpose. The column stores
     * one, so accepting `12.34` would silently round it to `12.3` — refusing is
     * more honest than quietly changing what was typed.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $drivingValue = ['required', 'numeric', 'decimal:0,1', 'min:0', 'max:9999.9'];
        $informingValue = ['nullable', 'numeric', 'decimal:0,1', 'min:0', 'max:9999.9'];

        return [
            // A catalogue searched several times a day is degraded by duplicates,
            // so the same name twice is refused. Soft-deleted rows are ignored:
            // deleting a food must not reserve its name forever.
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('foods')
                    ->where('user_id', $this->user()->id)
                    ->whereNull('deleted_at')
                    ->ignore($this->route('food')),
            ],
            'reference_quantity' => ['required', 'numeric', 'decimal:0,1', 'gt:0', 'max:9999.9'],
            'reference_unit' => ['required', Rule::enum(FoodUnit::class)],
            'protein_grams' => $drivingValue,
            'calories' => ['required', 'integer', 'min:0', 'max:65535'],
            'fibre_grams' => $drivingValue,
            'fat_grams' => $informingValue,
            'carbohydrate_grams' => $informingValue,
        ];
    }

    /**
     * Fill in the two values that only inform, so their fields stay optional.
     */
    protected function passedValidation(): void
    {
        $this->merge([
            'fat_grams' => $this->input('fat_grams') ?? 0,
            'carbohydrate_grams' => $this->input('carbohydrate_grams') ?? 0,
        ]);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique' => __('You already have a food with this name.'),
            '*.decimal' => __('Use at most one decimal, for example 12.3.'),
            '*.min' => __('This value can not be negative.'),
            'reference_quantity.gt' => __('The reference quantity must be greater than zero.'),
        ];
    }
}
