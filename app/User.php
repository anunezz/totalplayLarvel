<?php

namespace App;

use App\Http\Models\Cats\CatDeterminant;
use App\Http\Models\Cats\CatProfile;
use App\Http\Models\FirstSesion;
use App\Http\Models\Cats\CatCodePromotion;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Static_;

/**
 * App\User
 *
 * @property int $id
 * @property string $username
 * @property int $cat_profile_id
 * @property int $cat_unit_id
 * @property string $name
 * @property string $firstName
 * @property string|null $secondName
 * @property int $isActive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read mixed $full_name
 * @property-read mixed $hash
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCatProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCatUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSecondName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUsername($value)
 * @mixin \Eloquent

 * @property-read \App\Http\Models\Cats\CatProfile $profile
 * @method static \Illuminate\Database\Eloquent\Builder|User search($search)


 * @method static \Illuminate\Database\Eloquent\Builder|User whereCatMissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCatOrganismId($value)
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = ['username','cat_profile_id','code_id','email','name','password','firstName', 'secondName'];
    protected $appends = ['full_name', 'hash'];
    protected $with = ['profile','firstSesion','code'];

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->firstName . ' ' . $this->secondName;
    }

    public function getHashAttribute()
    {
        return encrypt( $this->id );
    }

    public function profile()
    {
        return $this->belongsTo(
            CatProfile::class,
            'cat_profile_id'
        )->where( 'isActive', 1 );
    }

    public function firstSesion()
    {
        return $this->belongsTo(
            FirstSesion::class,
            'id','user_id'
        );
    }

    public function code()
    {
        return $this->belongsTo(
            CatCodePromotion::class,
            'code_id'
        );
    }

    public function scopeSearch($query, $search)
    {
        return $query->when( !empty ( $search ), function ($query) use ($search) {
            return $query->where( function ($q) use ($search) {
                $q->orwhere( 'username', $search['username'] );
                $q->orWhere( 'email', $search['email']);
    //             $q->orWhereHas('profile', function($q) use ($search) {
    //                 $q->where('name', 'like', '%' .$search . '%');
    //             });

            });
        } );
    }
}
