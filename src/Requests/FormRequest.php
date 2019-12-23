<?php

namespace Grocelivery\Utils\Requests;

use Grocelivery\Utils\Models\User;
use Grocelivery\Utils\Services\JwtDecoder;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

/**
 * Class FormRequest
 * @package Grocelivery\Utils\Requests
 */
class FormRequest extends Request
{
    /**
     * @throws ValidationException
     */
    public function validate()
    {
        /** @var Validator $validator */
        $validator = app()->make('validator')->make($this->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * @param null $guard
     * @return User
     */
    public function user($guard = null): User
    {
        $decoder = new JwtDecoder();
        return $decoder->fetchUser($this->header('Authorization'));
    }

    /**
     * @return array
     */
    protected function rules(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function messages(): array
    {
        return [];
    }
}