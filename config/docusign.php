<?php

return [

    /** Configuration file for the example
    * The Integration Key is the same as the client id
    */
    'DS_CLIENT_ID' => env('DS_CLIENT_ID'),

    /*
    * API user (sender)
    * The sender's email can't be used here.
    * This is the guid for the impersonated user (the sender).
    */
    'DS_IMPERSONATED_USER_GUID' => env('DS_IMPERSONATED_USER_GUID'),

    /*
    * Signer email
    */
    'SIGNER_EMAIL' => env('SIGNER_EMAIL'),

    /** Signer name*/
    'SIGNER_NAME' => env('SIGNER_NAME'),

    /** Carbon copy email*/
    'CC_EMAIL' => env('CC_EMAIL'),

    /** Carbon copy name*/
    'CC_NAME' => env('CC_NAME'),

    /*
    * private key string
    * Include the BEGIN/END RSA PRIVATE KEY lines.
    * See the Readme for additional information.
    * Surround the key with quotes since the key has
    * multiple lines.
    */
    'DS_PRIVATE_KEY' => env('DS_PRIVATE_KEY'),


    /** The DS Authentication server*/
    'DS_AUTH_SERVER' => env('DS_AUTH_SERVER'),

    /*
    * If the DS_TARGET_ACCOUNT is false then the impersonated user's default
    * account will be used.
    * target account id
    */
    'DS_TARGET_ACCOUNT_ID' => env('DS_TARGET_ACCOUNT_ID'),

    'JWT_SCOPE' => 'signature',
];
