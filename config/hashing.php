<?php

/**
 * @link https://readouble.com/laravel/9.x/ja/hashing.html
 */
return [

    /*
    |--------------------------------------------------------------------------
    | デフォルトのハッシュドライバ
    |--------------------------------------------------------------------------
    |
    | このオプションは，アプリケーションのパスワードのハッシュ化に使用する
    | デフォルトのハッシュドライバを制御します．デフォルトでは bcrypt アルゴリズムが
    | 使われますが，必要に応じてこのオプションを自由に変更できます．
    |
    | Supported: "bcrypt", "argon", "argon2id"
    |
    */

    'driver' => 'bcrypt',

    /*
    |--------------------------------------------------------------------------
    | Bcryptオプション
    |--------------------------------------------------------------------------
    |
    | ここでは，Bcryptアルゴリズムを使用してパスワードをハッシュ化する際に
    | 使用する設定オプションを指定することができます．これにより，与えられた
    | パスワードのハッシュ化にかかる時間を制御することができます．
    |
    */

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Argon オプション
    |--------------------------------------------------------------------------
    |
    | ここでは，Argon アルゴリズムを使用してパスワードをハッシュ化する際に
    | 使用する設定オプションを指定することができます．これらによって，与えられた
    | パスワードのハッシュ化にかかる時間を制御することができます．
    |
    */

    'argon' => [
        'memory' => 65536,
        'threads' => 1,
        'time' => 4,
    ],

];
