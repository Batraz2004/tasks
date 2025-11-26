<?php

namespace App\Http\Requests;

use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskCreateRequest extends FormRequest
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
            'title' => ['required', 'max:250'],
            'description' => ['required', 'max:500'],
            'status' => ['required', Rule::enum(TaskStatusEnum::class)],
            'parent_id' => ['nullable', 'integer', 'exists:tasks,id'],
            'file_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg']
        ];
    }

    public function getData()
    {
        $data = $this->only(['title', 'description', 'status', 'parent_id']);

        return $data;
    }
}
