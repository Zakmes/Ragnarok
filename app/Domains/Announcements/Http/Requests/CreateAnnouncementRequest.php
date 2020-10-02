<?php

namespace App\Domains\Announcements\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateAnnouncementRequest
 *
 * @package App\Domains\Announcements\Requests
 */
class CreateAnnouncementRequest extends FormRequest
{
    /**
     * The validation rules for the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'max:255', 'string'],
            'message' => ['required', 'string'],
            'start_date' => ['date_format:d-m-Y', 'after:today'],
            'end_date' => ['date_format:d-m-Y', 'after:start_date'],
        ];
    }
}
