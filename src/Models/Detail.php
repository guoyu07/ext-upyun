<?php
/**
 * Created by PhpStorm.
 * User: bc-014
 * Date: 17-7-10
 * Time: 下午8:55
 */
namespace Notadd\Cloud\Models;

use Notadd\Foundation\Database\Model;
use Carbon\Carbon;

/**
 * Class Detail.
 */
class Detail extends Model
{
    /**
     * @var string
     */
    protected $table = 'ext_cloud_details';

    /**
     * @var
     */
    protected $url;

    /**
     * @var
     */
    protected $domain;

    /**
     * @var
     */
    protected $token;

    /**
     * @var array
     */
    protected $appends = ['url', 'thumb_url'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        Carbon::setLocale('zh');
    }

    /**
     * @param $path
     *
     * @return string
     */
    protected function withtoken($path)
    {
        $setting = app('setting');
        $is_token = $this->is_token;
        $domain = $is_token ? $setting->get('upyun.private.domain') : $setting->get('upyun.domain');   //is_token 1  是私有   0 是公开
        $tokenEnabled = $setting->get('upyun.private.tokenEnabled');
        $tokenTime = $setting->get('upyun.private.tokenTime');
        $url = $domain . $path;
        if ($is_token && $tokenEnabled) {
            $token = $setting->get('upyun.private.token');
            $etime = time() + $tokenTime;
            $_upt = substr(md5($token . '&' . $etime . '&' . $path), 12, 8) . $etime;
            $url = $domain . $path . '?_upt=' . $_upt;
        }

        return $url;
    }

    public function getSizeAttribute($size)
    {
        $units = [' B', ' KB', ' MB', ' GB', ' TB'];
        for ($i = 0; $size >= 1024 && $i < 4; $i++) {
            $size /= 1024;
        }

        return $this->attributes['size'] = round($size, 2) . $units[$i];
    }

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        $path = $this->path;

        return $this->attributes['url'] = $this->withtoken($path);
    }

    /**
     * @return string
     */
    public function getThumbUrlAttribute()
    {
        $path = $this->path;
        if (starts_with($this->ori_type, 'image')) {
            $req = app('request');
            switch ($req->mode) {
                case '0':
                    $thumb = '!/fwfh/' . $req->width . 'x' . $req->height;
                    break;
                case '1':
                    $thumb = '!/min/' . $req->width;
                    break;
                case '2':
                    $thumb = '!/fw/' . $req->width;
                    break;
                case '3':
                    $thumb = '!/fh/' . $req->width;
                    break;
                case '4':
                    $thumb = '!/sq/' . $req->width;
                    break;
                default:
                    $thumb = '!' . $req->thumb;
                    break;

            }
            $thumbPath = $path . $thumb;

            return $this->attributes['thumb_url'] = $this->withtoken($thumbPath);
        }
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->diffForHumans();
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->diffForHumans();
    }

    public function getQueueableConnection()
    {
        // TODO: Implement getQueueableConnection() method.
    }

}