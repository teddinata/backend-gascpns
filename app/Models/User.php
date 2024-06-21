<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'username',
        'avatar',
        'status',
        'birthdate',
        'last_login',
        'wallet_balance',
        'referral_code',
        'otp',
        'otp_expired_at',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function markEmailAsVerified()
    {
        $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * generate referral code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->referral_code = self::generateReferralCode($user->name);
        });
    }

    private static function generateReferralCode($name)
    {
        $namePart = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $name), 0, 4)); // Ambil 3 huruf pertama dari nama
        $randomPart = strtoupper(Str::random(4)); // 3 karakter acak

        $code = 'GAS' . $namePart . $randomPart;

        // Pastikan kode referral unik
        while (self::where('referral_code', $code)->exists()) {
            $randomPart = strtoupper(Str::random(3));
            $code = 'GAS' . $namePart . $randomPart;
        }

        return $code;
    }

    protected $appends = ['age'];

    public function getAgeAttribute()
    {
        try {
            // Ensure birthdate is valid
            $birthdate = Carbon::parse($this->birthdate);
            $now = Carbon::now();

            // Calculate years, months, and days
            $years = round($birthdate->diffInYears($now));
            $months = $birthdate->copy()->addYears($years)->diffInMonths($now);
            $days = $birthdate->copy()->addYears($years)->addMonths($months)->diffInDays($now);

            // Ensure months and days are rounded to whole numbers for clarity
            $roundedMonths = floor($months);
            $roundedDays = floor($days);

            $ageString = "";

            // Display years as rounded whole numbers
            if ($years > 0) {
                $ageString .= "$years tahun";
            }

            // Only add months if non-zero
            if ($roundedMonths > 0) {
                $ageString .= " $roundedMonths bulan";
            }

            // Only add days if non-zero
            if ($roundedDays > 0) {
                $ageString .= " $roundedDays hari";
            }

            return $ageString;
        } catch (\Exception $e) {
            // Handle exceptions gracefully
            return "Invalid birthdate";
        }
    }




    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_students', 'user_id', 'course_id');
    }

    public function loginAttempts()
    {
        return $this->hasOne(LoginAttempt::class, 'email', 'email');
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'user_id', 'id');
    }

    public function packageTryOuts()
    {
        return $this->belongsToMany(PackageTryOut::class, 'course_students', 'user_id', 'package_tryout_id');
    }
    public function enrolledPackageTryouts()
    {
        // return $this->belongsToMany(PackageTryOut::class, 'package_tryout_enrollments', 'user_id', 'package_tryout_id');
        return $this->belongsToMany(Package::class, 'course_students', 'user_id', 'package_tryout_id');
    }

    // package
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'course_students', 'user_id', 'package_tryout_id');
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referred_by');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'student_id', 'id');
    }

    public function tryouts()
    {
        return $this->hasMany(TryOut::class, 'user_id', 'id');
    }

    public function topUpTransactions()
    {
        return $this->hasMany(TopUpTransaction::class, 'user_id', 'id');
    }


}
