<?php


namespace App\models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


/**
 * An Eloquent Model: 'User'
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string $username
 * @property string $password
 * @property string $invitationCode
 * @property string $link
 * @property integer $cityId
 * @property integer $level
 * @property boolean $uploadPhoto
 * @property boolean $status
 * @method static \Illuminate\Database\Query\Builder|\App\models\User whereUserName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\User whereLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\User whereInvitationCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\User wherePhone($value)
 */

class User extends Authenticatable{

    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'users';
    protected $primaryKey = 'id';
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    protected $fillable = [
        'username', 'password'
    ];

    protected $hidden = array('password', 'remember_token');

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function getAuthIdentifier() {
        return $this->getKey();
    }
    public function getAuthPassword() {
        return $this->password;
    }

    public static function whereId($value) {
        return User::find($value);
    }

    public function getUserActivityCount(){
        $user = \Auth::user();

        $postCount = 0;
        $picCount = 0;
        $videoCount = 0;
        $video360Count = 0;
        $questionCount = 0;
        $ansCount = 0;
        $scoreCount = 0;
        $postActivity = Activity::whereName('نظر')->first();
        $questionActivity = Activity::whereName('سوال')->first();
        $ansActivity = Activity::whereName('پاسخ')->first();
        $postCount += LogModel::whereActivityId($postActivity->id)->where('visitorId', $user->id)->count();
        $picCount += PhotographersPic::where('userId', $user->id)->count();
        $picCount += \DB::select('SELECT COUNT(reviewPics.id) AS count FROM reviewPics RIGHT JOIN log ON log.visitorId = ' . $user->id . ' AND reviewPics.isVideo = 0 AND reviewPics.is360 = 0 AND reviewPics.logId = log.id ')[0]->count;
        $videoCount += \DB::select('SELECT COUNT(reviewPics.id) AS count FROM reviewPics RIGHT JOIN log ON log.visitorId = ' . $user->id . ' AND reviewPics.isVideo = 1 AND reviewPics.is360 = 0 AND reviewPics.logId = log.id ')[0]->count;
        $video360Count += \DB::select('SELECT COUNT(reviewPics.id) AS count FROM reviewPics RIGHT JOIN log ON log.visitorId = ' . $user->id . ' AND reviewPics.isVideo = 1 AND reviewPics.is360 = 1 AND reviewPics.logId = log.id ')[0]->count;
        $questionCount += LogModel::whereActivityId($questionActivity->id)->where('visitorId', $user->id)->count();
        $ansCount += LogModel::whereActivityId($ansActivity->id)->where('visitorId', $user->id)->count();
        $scoreCount += count(\DB::select('SELECT questionUserAns.logId as PlaceCount FROM questionUserAns INNER JOIN log ON log.visitorId = ' . $user->id . ' AND questionUserAns.logId = log.id GROUP BY PlaceCount'));
        $userCount = [
            'postCount' => $postCount,
            'picCount' => $picCount,
            'videoCount' => $videoCount,
            'video360Count' => $video360Count,
            'questionCount' => $questionCount,
            'ansCount' => $ansCount,
            'scoreCount' => $scoreCount,
        ];

        return $userCount;
    }

    public function getUserTotalPoint()
    {
        return getUserPoints(auth()->user()->id);
    }

    public function getUserNearestLevel()
    {
        return nearestLevel(auth()->user()->id);
    }

    public function deleteUser(){
//        $uId = \Auth::user()->id;
//        ActivationCode::where('userId', $uId)->delete();
//        BannerPics::where('userId', $uId)->update(['userId' => 0]);
//        $logs = LogModel::where('visitorId', $uId)->get();
//        foreach ($logs as $item){
//
//        }
    }
}