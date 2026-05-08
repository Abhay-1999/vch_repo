<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $supplier = $this->route('supplierMaster');

        return [

            /*
            |--------------------------------------------------------------------------
            | Basic Supplier Details
            |--------------------------------------------------------------------------
            */

            'supplier_id' => [

                'required',

                'string',

                'max:20',

                Rule::unique('supplier_masters', 'supplier_id')
                    ->ignore($supplier),
            ],

            'supplier_name' => [
                'required',
                'string',
                'max:255',
            ],

            'trade_brand_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'category' => [
                'nullable',
                Rule::in([
                    'Raw Material',
                    'Packaging',
                    'Beverages',
                    'Vegetables',
                    'Dairy',
                    'Stationary',
                    'Cleaning Material',
                    'Other'
                ]),
            ],

            /*
            |--------------------------------------------------------------------------
            | Contact Information
            |--------------------------------------------------------------------------
            */

            'contact_person' => [
                'nullable',
                'string',
                'max:150',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:100',
            ],

            'mobile_no' => [
                'nullable',
                'digits:10',
            ],

            'alt_phone' => [
                'nullable',
                'digits:10',
            ],

            'email_id' => [
                'nullable',
                'email',
                'max:150',
            ],

            /*
            |--------------------------------------------------------------------------
            | Address Information
            |--------------------------------------------------------------------------
            */

            'address_line1' => [
                'nullable',
                'string',
                'max:500',
            ],

            'address_line2' => [
                'nullable',
                'string',
                'max:500',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'state' => [
                'nullable',
                'string',
                'max:100',
            ],

            'pincode' => [
                'nullable',
                'digits:6',
            ],

            'country' => [
                'nullable',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Tax & Legal Information
            |--------------------------------------------------------------------------
            */

            'gstin' => [

                'nullable',

                'string',

                'size:15',

                'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
            ],

            'pan' => [

                'nullable',

                'string',

                'size:10',

                'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            ],

            'fssai_license_no' => [
                'nullable',
                'digits_between:10,20',
            ],

            'fssai_expiry' => [
                'nullable',
                'date',
            ],

            'msme_udyam_no' => [
                'nullable',
                'string',
                'max:50',
            ],

            /*
            |--------------------------------------------------------------------------
            | Bank Details
            |--------------------------------------------------------------------------
            */

            'bank_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'bank_account_no' => [
                'nullable',
                'string',
                'max:50',
            ],

            'ifsc_code' => [

                'nullable',

                'string',

                'size:11',

                'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            ],

            'account_holder_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            /*
            |--------------------------------------------------------------------------
            | Payment Settings
            |--------------------------------------------------------------------------
            */

            'payment_terms' => [
                'nullable',
                'string',
                'max:100',
            ],

            'credit_limit' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'nullable',
                'string',
                'max:10',
            ],

            'tds_applicable' => [
                'nullable',
                Rule::in(['Yes', 'No']),
            ],

            'tds_rate' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'lead_time_days' => [
                'nullable',
                'integer',
                'min:0',
                'max:365',
            ],

            /*
            |--------------------------------------------------------------------------
            | Status & Audit
            |--------------------------------------------------------------------------
            */

            'rating' => [
                'nullable',
                'integer',
                'min:1',
                'max:5',
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'Active',
                    'Inactive',
                    'Blocked'
                ]),
            ],

            'onboarded_on' => [
                'nullable',
                'date',
            ],

            'onboarded_by' => [
                'nullable',
                'string',
                'max:100',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Messages
    |--------------------------------------------------------------------------
    */

    public function messages(): array
    {
        return [

            'supplier_id.required' =>
                'Supplier ID is required.',

            'supplier_id.unique' =>
                'This Supplier ID already exists.',

            'supplier_name.required' =>
                'Supplier name is required.',

            'mobile_no.digits' =>
                'Mobile number must be 10 digits.',

            'alt_phone.digits' =>
                'Alternate mobile number must be 10 digits.',

            'gstin.size' =>
                'GSTIN must be exactly 15 characters.',

            'gstin.regex' =>
                'Invalid GSTIN format.',

            'pan.size' =>
                'PAN must be exactly 10 characters.',

            'pan.regex' =>
                'Invalid PAN format.',

            'ifsc_code.size' =>
                'IFSC code must be exactly 11 characters.',

            'ifsc_code.regex' =>
                'Invalid IFSC code format.',

            'pincode.digits' =>
                'Pincode must be 6 digits.',

            'rating.min' =>
                'Rating must be between 1 to 5.',

            'rating.max' =>
                'Rating must be between 1 to 5.',

            'tds_rate.max' =>
                'TDS rate cannot exceed 100%.',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX Validation Response
    |--------------------------------------------------------------------------
    */

    protected function failedValidation(
        Validator $validator
    ): void {

        throw new HttpResponseException(

            response()->json([

                'success' => false,

                'message' => 'Validation failed.',

                'errors' => $validator->errors(),

            ], 422)

        );
    }
}