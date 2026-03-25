<?php

use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the `pest()` function to bind a different class or interface.
|
*/

pest()->extend(TestCase::class);

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that certain values match your
| expectations. The "expect()" function is a powerful tool for doing just that.
| When used with custom expectations, you can make your tests more readable and
| expressive.
|
*/

// Expect::extend('toBeOne', function () {
//     return $this->toBe(1);
// });

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have a set of
| custom functions that you use frequently. You can define them here.
|
*/

// function something()
// {
//     // ..
// }
