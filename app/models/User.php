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

//include_once 'app\Http\Controllers\Common.php';

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

    public static function getUserActivityCount($uId){
        $user = User::find($uId);

        $postCount = 0;
        $picCount = 0;
        $videoCount = 0;
        $video360Count = 0;
        $questionCount = 0;
        $ansCount = 0;
        $scoreCount = 0;
        $addPlace = 0;

        if($user != null) {
            $postActivity = Activity::whereName('نظر')->first();
            $questionActivity = Activity::whereName('سوال')->first();
            $ansActivity = Activity::whereName('پاسخ')->first();
            $postCount = LogModel::whereActivityId($postActivity->id)->where('visitorId', $user->id)->count();
            $picCount = PhotographersPic::where('userId', $user->id)->count();

            $picLog = ReviewPic::where('isVideo', 0)->where('is360', 0)->pluck('logId')->toArray();
            $picCount += LogModel::whereIn('id', $picLog)->where('visitorId', $user->id)->count();

            $videoLog = ReviewPic::where('isVideo', 1)->where('is360', 0)->pluck('logId')->toArray();
            $videoCount = LogModel::whereIn('id', $videoLog)->where('visitorId', $user->id)->count();

            $videoCLog = ReviewPic::where('isVideo', 1)->where('is360', 1)->pluck('logId')->toArray();
            $video360Count = LogModel::whereIn('id', $videoCLog)->where('visitorId', $user->id)->count();

            $questionCount = LogModel::whereActivityId($questionActivity->id)->where('visitorId', $user->id)->count();
            $ansCount = LogModel::whereActivityId($ansActivity->id)->where('visitorId', $user->id)->count();
            $scoreCount = count(\DB::select('SELECT questionUserAns.logId as PlaceCount FROM questionUserAns INNER JOIN log ON log.visitorId = ' . $user->id . ' AND questionUserAns.logId = log.id GROUP BY PlaceCount'));
            $addPlace = UserAddPlace::where('userId', $user->id)->count();
        }

        $userCount = [
            'postCount' => $postCount,
            'picCount' => $picCount,
            'videoCount' => $videoCount,
            'video360Count' => $video360Count,
            'questionCount' => $questionCount,
            'ansCount' => $ansCount,
            'scoreCount' => $scoreCount,
            'addPlace' => $addPlace,
        ];

        return $userCount;
    }

    public function getUserTotalPoint()
    {
        return $this->getUserPointInModel(auth()->user()->id);
    }

    public function getUserNearestLevel()
    {
        return $this->nearestLevelInModel(auth()->user()->id);
    }

    public static function nearestLevelInModel($uId)
    {
        $points = User::getUserPointInModel($uId);
        $currLevel = Level::where('floor', '<=', $points)->orderBy('floor', 'DESC')->first();

        if($currLevel == null)
            $currLevel = Level::orderBy('floor', 'ASC')->first();

        $nextLevel = Level::where('floor', '>', $points)->orderBy('floor', 'ASC')->first();

        if($nextLevel == null)
            $nextLevel = Level::orderBy('floor', 'ASC')->first();

        return [$currLevel, $nextLevel];
    }

    public static function getUserPointInModel($uId)
    {
        $points = \DB::select("SELECT SUM(activity.rate) as total FROM log, activity WHERE confirm = 1 and log.visitorId = " . $uId . " and log.activityId = activity.id");

        if($points == null || count($points) == 0 || $points[0]->total == "")
            return 0;

        return $points[0]->total;
    }

    public function getUserPicInModel($id = 0)
    {
        $user = User::find($id);
        if($user != null){
            if(strpos($user->picture, 'http') !== false)
                return $user->picture;
            else{
                if($user->uploadPhoto == 0){
                    $deffPic = DefaultPic::find($user->picture);

                    if($deffPic != null)
                        $uPic = \URL::asset('defaultPic/' . $deffPic->name);
                    else
                        $uPic = \URL::asset('_images/nopic/blank.jpg');
                }
                else
                    $uPic = \URL::asset('userProfile/' . $user->picture);
            }
        }
        else
            $uPic = \URL::asset('_images/nopic/blank.jpg');

        return $uPic;
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