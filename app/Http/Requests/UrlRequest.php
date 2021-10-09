<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UrlRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('link')) {
            $link = $this->link;

            if (strpos($link, 'http://') === false && strpos($link, 'https://') === false) {
                $this->merge(['link' => 'http://' . $link]);
            }
            if(substr($link, -1) !== '/') {
                $this->merge(['link' => $link . '/']);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $regex = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-])\/?/';

        return [
            'link' => 'required|regex:'.$regex,
        ];
    }
}
