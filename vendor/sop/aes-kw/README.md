[![Build Status](https://travis-ci.org/sop/aes-kw.svg?branch=master)](https://travis-ci.org/sop/aes-kw)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sop/aes-kw/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sop/aes-kw/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/sop/aes-kw/badge.svg?branch=master)](https://coveralls.io/github/sop/aes-kw?branch=master)
[![License](https://poser.pugx.org/sop/aes-kw/license)](https://github.com/sop/aes-kw/blob/master/LICENSE)

# AES Key Wrap
A PHP library for AES Key Wrap
([RFC 3394](https://tools.ietf.org/html/rfc3394))
algorithm with padding
([RFC 5649](https://tools.ietf.org/html/rfc5649))
support.

Supports AES key sizes of 128, 192 and 256 bits.

## Installation
This library is available on
[Packagist](https://packagist.org/packages/sop/aes-kw).

    composer require sop/aes-kw

## Code examples
Here are some simple usage examples. Namespaces are omitted for brevity.

### Wrap a 128 bit key with AES-128
Wrap a key of 16 bytes using a 16-byte key encryption key.

```php
$kek = "0123456789abcdef"; // 128-bit key encryption key
$key = "MySecretPassword"; // key to encrypt
$algo = new AESKW128();
$ciphertext = $algo->wrap($key, $kek);
echo bin2hex($ciphertext);
```

Outputs:

    89efdbc3501f1f5e952a4bbae1329c9f1a47b9fd61b48dee

### Unwrap a key
Unwrap a key from previous example. `$kek` and `$algo` variables are the same.
`$ciphertext` variable contains the output from a wrapping procedure.

```php
$key = $algo->unwrap($ciphertext, $kek);
echo $key;
```

Outputs:

    MySecretPassword

### Wrap an arbitrary length passphrase with AES-192
Wrapping a key that is not a multiple of 64 bits requires padding.

```php
$kek = "012345678901234567890123"; // 192-bit key encryption key
$key = "My hovercraft is full of eels."; // passphrase to encrypt
$algo = new AESKW192();
$ciphertext = $algo->wrapPad($key, $kek);
echo bin2hex($ciphertext);
```

Outputs:

    f319811450badfe4385b5534bf26fa6f9fdcd1a593b3ae6b707f15c1015bbf3faf58619818bd8784

### Unwrap a key with padding
Key that was wrapped with padding must be unwrapped with `unwrapPad`.

```php
$key = $algo->unwrapPad($ciphertext, $kek);
echo $key;
```

Outputs:

    My hovercraft is full of eels.

## License
This project is licensed under the MIT License.
