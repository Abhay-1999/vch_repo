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
        $id = $this->route('supplierMaster'); // route model binding

        return [
            // ── Basic ────────────────────────────────────────────────
            'supp_name' => 'required|string|max:255',

            'supp_code' => [
                'required',
                Rule::unique('supplier_masters', 'supp_code')->ignore($id),
            ],
            'supp_type'       => ['nullable', 'in:Local,Outside,Manufacturer,Wholesaler,Farmer,Import'],
            'gst_no'          => [
                'nullable',
                'string',
                'size:15',
                'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
            ],

            // ── Food Supply ──────────────────────────────────────────
            'supply_category' => ['required', 'in:Vegetables,Fruits,Dairy,Meat,Seafood,Dry Goods,Beverages,Bakery,Oils,Packaging,Cleaning,Other'],
            'fssai_no'        => ['nullable', 'digits:14'],
            'fssai_expiry'    => ['nullable', 'date', 'after:today'],
            'delivery_days'   => ['nullable', 'in:Daily,Alternate,Weekly,On Order'],
            'delivery_slot'   => ['nullable', 'in:Early Morning,Morning,Afternoon,Evening'],
            'min_order_value' => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'lead_time_days'  => ['nullable', 'integer', 'min:0', 'max:365'],
            'items_supplied'  => ['nullable', 'string', 'max:500'],
            'quality_grade'   => ['nullable', 'in:A,B,C'],
            'is_organic'      => ['nullable', 'in:Yes,No,Partial'],

            // ── Address ──────────────────────────────────────────────
            'supp_add1'       => ['nullable', 'string', 'max:200'],
            'supp_add2'       => ['nullable', 'string', 'max:200'],
            'market_name'     => ['nullable', 'string', 'max:100'],
            'city'            => ['nullable', 'string', 'max:100'],
            'state'           => ['nullable', 'string', 'max:100'],
            'pincode'         => ['nullable', 'digits:6'],
            'country'         => ['nullable', 'string', 'max:100'],

            // ── Contact ──────────────────────────────────────────────
            'contact_person'  => ['nullable', 'string', 'max:100'],
            'contact_no'      => ['required', 'digits:10'],
            'alt_contact_no'  => ['nullable', 'digits:10'],
            'whatsapp_no'     => ['nullable', 'digits:10'],
            'email'           => ['nullable', 'email', 'max:150'],
            'pan_no'          => [
                'nullable',
                'string',
                'size:10',
                'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            ],

            // ── Financial ────────────────────────────────────────────
            'opening_balance' => ['nullable', 'numeric', 'min:0'],
            'credit_limit'    => ['nullable', 'numeric', 'min:0'],
            'payment_terms'   => ['nullable', 'integer', 'min:0', 'max:365'],
            'payment_mode'    => ['nullable', 'in:Cash,Cheque,NEFT,UPI,Credit'],
            'discount_pct'    => ['nullable', 'numeric', 'min:0', 'max:100'],

            // ── Bank ─────────────────────────────────────────────────
            'bank_name'       => ['nullable', 'string', 'max:100'],
            'account_no'      => ['nullable', 'string', 'max:30'],
            'ifsc'            => [
                'nullable',
                'string',
                'size:11',
                'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            ],
            'upi_id'          => ['nullable', 'string', 'max:100', 'regex:/^[\w.\-_]{3,}@[a-zA-Z]{3,}$/'],

            // ── Additional ───────────────────────────────────────────
            'status'          => ['nullable', 'in:Active,Inactive,Blacklisted'],
            'supplier_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'remark'          => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            // Basic
            'supp_name.required'       => 'Supplier name is required.',
            'supp_name.max'            => 'Supplier name cannot exceed 150 characters.',
            'supp_code.unique'         => 'This supplier code is already in use.',
            'gst_no.size'              => 'GST number must be exactly 15 characters.',
            'gst_no.regex'             => 'GST number format is invalid (e.g. 27AXXXX0000X1Z5).',

            // Food Supply
            'supply_category.required' => 'Please select a supply category.',
            'supply_category.in'       => 'Invalid supply category selected.',
            'fssai_no.digits'          => 'FSSAI license number must be exactly 14 digits.',
            'fssai_expiry.after'       => 'FSSAI expiry date must be a future date.',

            // Contact
            'contact_no.required'      => 'Mobile number is required.',
            'contact_no.digits'        => 'Mobile number must be exactly 10 digits.',
            'alt_contact_no.digits'    => 'Alternate mobile must be exactly 10 digits.',
            'whatsapp_no.digits'       => 'WhatsApp number must be exactly 10 digits.',
            'email.email'              => 'Please enter a valid email address.',
            'pan_no.size'              => 'PAN number must be exactly 10 characters.',
            'pan_no.regex'             => 'PAN number format is invalid (e.g. AXXXX0000X).',

            // Financial
            'discount_pct.max'         => 'Discount percentage cannot exceed 100.',
            'payment_terms.max'        => 'Payment terms cannot exceed 365 days.',

            // Bank
            'ifsc.size'                => 'IFSC code must be exactly 11 characters.',
            'ifsc.regex'               => 'IFSC code format is invalid (e.g. SBIN0001234).',
            'upi_id.regex'             => 'UPI ID format is invalid (e.g. name@upi).',

            // Additional
            'supplier_rating.min'      => 'Rating must be between 1 and 5.',
            'supplier_rating.max'      => 'Rating must be between 1 and 5.',
        ];
    }

    // ── Return JSON errors for AJAX ──────────────────────────────────
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}