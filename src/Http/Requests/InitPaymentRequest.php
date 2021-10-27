<?php

namespace Cr4sec\AppleAppsFlyer\Http\Requests;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use ReceiptValidator\iTunes\Validator as iTunesValidator;
use ReceiptValidator\RunTimeException;

class InitPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'idfa' => 'nullable|string',
            'appsflyer_id' => 'required|string',
            'currency' => 'required|string',
            'price' => 'required|numeric',
            'receipt' => 'required|string'
        ];
    }

    /**
     * @throws ValidationException
     * @throws GuzzleException
     * @throws RunTimeException
     */
    public function passedValidation()
    {
        $receiptBase64Data = $this->receipt;

        $receipt =
            (new iTunesValidator(config('apple-appsflyer.apple.mode') === 'production' ? iTunesValidator::ENDPOINT_PRODUCTION : iTunesValidator::ENDPOINT_SANDBOX))
                ->setSharedSecret(config('apple-appsflyer.apple.shared_secret'))
                ->setReceiptData($receiptBase64Data)
                ->validate();

        if (!$receipt->isValid()) {
            throw ValidationException::withMessages([
                'receipt' => sprintf(
                    'Receipt is not valid. Receipt result code is %s. User ID %d',
                    $receipt->getResultCode(),
                    auth()->user()->id
                )
            ]);
        }

        $this->merge(compact('receipt'));
    }
}
