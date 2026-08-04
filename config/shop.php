<?php

return [
    /*
     * Uniform GST rate applied across the catalog, expressed as a
     * percentage (e.g. 18 for 18%). Split evenly into CGST + SGST
     * for intra-state sales, or charged in full as IGST for inter-state.
     */
    'cgst_rate' => (float) env('SHOP_CGST_RATE', 9),
    'sgst_rate' => (float) env('SHOP_SGST_RATE', 8),
    'gst_rate' => (float) env('SHOP_GST_RATE', 18),

    /*
     * Seller / supplier details as they must appear on every GST invoice.
     * `state` is compared against each order's shipping_state to decide
     * CGST+SGST (intra-state) vs IGST (inter-state).
     */
    'company' => [
        'name' => env('SHOP_COMPANY_NAME', 'Xpert IT Solution'),
        'gstin' => env('SHOP_COMPANY_GSTIN', '03FJRPM3464D1Z4'),
        'address_line1' => env(
            'SHOP_COMPANY_ADDRESS_LINE1',
            'Vijay Partap Singh Market, Talwara Road, Near Sabji Mandi',
        ),
        'address_line2' => env(
            'SHOP_COMPANY_ADDRESS_LINE2',
            'Mukerian, Distt Hoshiarpur',
        ),
        'state' => env('SHOP_COMPANY_STATE', 'Punjab'),
        'state_code' => env('SHOP_COMPANY_STATE_CODE', '03'),
        'phone' => env('SHOP_COMPANY_PHONE', '6280004560'),
        'email' => env('SHOP_COMPANY_EMAIL', 'xpertitsolution6@gmail.com'),
        'bank_account_number' => env(
            'SHOP_COMPANY_BANK_ACCOUNT',
            '2092651100003292',
        ),
        'bank_ifsc' => env('SHOP_COMPANY_BANK_IFSC', 'IBKL0002092'),

        'signature_path' => env('SHOP_COMPANY_SIGNATURE_PATH', 'signature.png'),
        'logo_path' => env('SHOP_COMPANY_LOGO_PATH', 'logo.png'),
    ],
];
