<?php

namespace Api\Validation;

class MediaValidationRule extends Validation
{
    public function rules()
    {
        return [
            'email' => 'email|required',
            'sms' => 'string|required',
            'p_box' => 'string|required',
            'address' => [
                'address' => 'string|required',
                'city' => 'string|required',
                'post_code' => 'string|required',
            ],
        ];
    }
}
