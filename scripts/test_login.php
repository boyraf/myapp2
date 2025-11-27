php
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

$member = Member::where('email', 'pbergnaum@example.com')->first();
echo "Member found: " . ($member ? "yes (id=$member->id, email=$member->email)" : "no") . "\n";

if ($member) {
  $passwordCheck = Hash::check('Password@123', $member->password);
  echo "Password check: " . ($passwordCheck ? "pass" : "fail") . "\n";
  
  $attempt = Auth::guard('member')->attempt(['email' => 'pbergnaum@example.com', 'password' => 'Password@123']);
  echo "Guard attempt: " . ($attempt ? "success" : "failed") . "\n";
  
  if ($attempt) {
    echo "Auth::guard('member')->check(): " . (Auth::guard('member')->check() ? "yes" : "no") . "\n";
    $user = Auth::guard('member')->user();
    echo "Auth::guard('member')->user(): " . ($user ? "id=$user->id" : "null") . "\n";
  }
}
