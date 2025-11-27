<?php
// scripts/auth_check.php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

function out($msg) { echo $msg . PHP_EOL; }

out('Running member auth checks...');

$member = Member::first();
if (! $member) {
    out('NO_MEMBER_FOUND');
    exit(0);
}

out('FOUND_MEMBER: id=' . $member->id . ' email=' . $member->email);
out('HASHED_PASSWORD: ' . $member->password);

$testPassword = 'Password@123';
$out = Hash::check($testPassword, $member->password) ? 'PASSWORD_CHECK=PASS' : 'PASSWORD_CHECK=FAIL';
out($out);

$attempt = Auth::guard('member')->attempt(['email' => $member->email, 'password' => $testPassword]);
out('ATTEMPT_RESULT: ' . ($attempt ? 'true' : 'false'));

// show session driver and config
$outDriver = config('session.driver');
out('SESSION_DRIVER: ' . $outDriver);

// Done
exit(0);
