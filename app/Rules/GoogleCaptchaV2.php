<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use GuzzleHttp\Client;

class GoogleCaptchaV2 implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $client = new Client();
        
        $res = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => config('services.google.captcha.secret'),
                'response' => $value
            ]
        ]);

        $body = $res->getBody();
        $result = json_decode($body);

        return ($result->success === true);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The reCAPTCHA validation has failed, please try again.';
    }
}
