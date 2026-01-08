<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^[0-9]{9}$/'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];

        // if user is doctor, require doctor-related fields
        if ($this->user()->role && $this->user()->role->name === 'doctor') {
            $rules = array_merge($rules, [
                'specialization_id' => ['nullable','exists:specializations,id'],
                'description' => ['nullable','string'],
                'profile_picture' => ['nullable','image','mimes:jpeg,png,jpg,gif','max:2048'],
                'city' => ['nullable','string','max:255'],
                'postal_code' => ['nullable','regex:/^\d{2}-\d{3}$/'],
                'street' => ['nullable','string','max:255'],
                'house_number' => ['nullable','string','max:50'],
                'office_hours' => ['nullable','array'],
                'office_hours.*.day_of_week' => ['nullable','integer','between:1,7'],
                'office_hours.*.start_time' => ['nullable','date_format:H:i'],
                'office_hours.*.end_time' => ['nullable','date_format:H:i'],
            ]);
        }

        return $rules;
    }
}
