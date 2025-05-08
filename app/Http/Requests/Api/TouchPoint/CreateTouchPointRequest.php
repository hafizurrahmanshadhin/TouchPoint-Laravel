<?php

namespace App\Http\Requests\Api\TouchPoint;

use App\Helpers\Helper;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CreateTouchPointRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'avatar'                 => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:20480'],
            'name'                   => ['required', 'string', 'max:255'],
            'phone_number'           => ['required', 'string', 'max:25'],
            'contact_type'           => ['required', Rule::in(['personal', 'business'])],
            'contact_method'         => ['required', Rule::in(['call', 'text', 'meetup'])],
            'touch_point_start_date' => ['required', 'date'],
            'touch_point_start_time' => ['nullable', 'date_format:H:i'],
            'frequency'              => ['required', Rule::in(['daily', 'weekly', 'monthly', 'custom'])],
            'custom_days'            => [
                'integer', 'min:1', 'max:365',
                'required_if:frequency,custom',
                'prohibited_unless:frequency,custom',
            ],
            'notes'                  => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    protected function withValidator(Validator $validator) {
        $validator->after(function ($validator) {
            $date = $this->input('touch_point_start_date');
            $time = $this->input('touch_point_start_time');
            if ($date && $time) {
                try {
                    $dt = Carbon::createFromFormat('Y-m-d H:i', "$date $time");
                    if ($dt->isPast()) {
                        $validator->errors()->add(
                            'touch_point_start_date',
                            'The start date and time must be present or in the future.'
                        );
                    }
                } catch (Exception $e) {
                    // ignore parse errors; the date_format rule will catch them
                    Log::error('Date parsing error: ' . $e->getMessage());
                }
            }
        });
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(
            Helper::jsonResponse(false, 'Validation error.', 422, null, $validator->errors())
        );
    }
}
