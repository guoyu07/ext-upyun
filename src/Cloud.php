<?php
/**
 * This file is part of Notadd.
 *
 * @author        linxing <linxing@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      17-6-23 下午12:14
 */

namespace Notadd\Cloud;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Upyun\Config;
use Upyun\Upyun;
use Upyun\Util;
use Notadd\Cloud\Models\Detail;

/**
 * Class Cloud.
 */
class Cloud
{
    protected $cloud;

    protected $is_token;

    /**
     * Cloud constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Notadd\Foundation\Setting\Contracts\SettingsRepository $settings
     */
    public function __construct(Request $request, SettingsRepository $settings)
    {
        $this->request = $request;
        $this->settings = $settings;
        $this->is_token = $this->request->is_token;

        $this->bucketName = $this->is_token ? $this->settings->get('upyun.private.bucketName') : $this->settings->get('upyun.bucketName');
        $this->folder = $this->is_token ? $this->settings->get('upyun.private.folder') : $this->settings->get('upyun.folder');
        $this->formApiSecret = $this->is_token ? $this->settings->get('upyun.private.form_api_secret') : $this->settings->get('upyun.form_api_secret');
        $this->Operator = $this->is_token ? $this->settings->get('upyun.private.operatorName') : $this->settings->get('upyun.operatorName');
        $this->operatorPassword = $this->is_token ? $this->settings->get('upyun.private.operatorPassword') : $this->settings->get('upyun.operatorPassword');
        $config = new Config($this->bucketName, $this->Operator, $this->operatorPassword);
        $config->processNotifyUrl = route('videoThumbNotify');
        $this->cloud = new Upyun($config);
    }

    /**
     * @return bool|mixed
     */
    public function upload()
    {
        $file = $this->request->file;
        $content = fopen($file, 'r');
        $name = $file->getClientOriginalName();
        $hash = Util::md5Hash($content);
        $folder = '/notadd/';
        $path = $folder . $name;
        $exist = $this->cloud->has($path);
        if ($exist) {
            $info['code'] = 205;
            $info['data'] = 'file exist';

            return $info;
        } else {
            $extension = $file->extension();
            $image = ['jpeg', 'png', 'bmp', 'gif', 'svg', 'webp', 'jpg'];
            if (in_array($extension, $image)) {
                $uploadModeImage = $this->settings->get('upyun.uploadModeImage');
                switch ($uploadModeImage) {
                    case 'ori':
                        $apps = [];
                        break;
                    case 'webp100':
                        $apps = ["x-gmkerl-thumb" => "/format/webp/lossless/true",];
                        break;
                    case 'webp75':
                        $apps = ["x-gmkerl-thumb" => "/format/webp",];
                        break;
                    default:
                        $apps = ["x-gmkerl-thumb" => "/format/webp",];
                }
            } else {
                $apps = [];
            }
            $result = $this->cloud->write($path, $content, $apps);
            if ($result) {
                return true;
            }

        }

    }

    public function getOptions()
    {
        $bucket = $this->bucketName;
        $folder = $this->folder;
        $upload_type = $this->request->upload_type;

        $options = [];
        $options['bucket'] = $bucket; /// 空间名
        $options['expiration'] = time() + 600; // 授权过期时间
        $options['notify-url'] = route('notify'); // 服务端异步回调地址
//

        $random = Str::random(20);

        if ($upload_type == 'image') {
            $uploadModeImage = $this->settings->get('upyun.uploadModeImage');
            $options['allow-file-type'] = 'jpg,jpeg,gif,png,webp,bmp,svg';
            $options['save-key'] = ($uploadModeImage == 'ori') ? '/' . $folder . '/{filemd5}{.suffix}' : '/' . $folder . '/{filemd5}' . '.webp';
            $options['x-gmkerl-exif-switch'] = ($uploadModeImage == 'ori') ? false : true;
            if ($uploadModeImage == 'webp75') {
                $options['x-gmkerl-thumb'] = '/format/webp';
//                $options['apps'] = [
//                    '0' => [
//                        'name'           => 'thumb',
//                        'x-gmkerl-thumb' => '/sq/300/format/webp',
//                        'save_as'    => '/' . $folder . '/' . $random . '.webp',
//                        'notify_url'     => route('apppsnotify'),
//                    ],
//                ];
            }
            if ($uploadModeImage == 'webp100') {
                $options['x-gmkerl-thumb'] = '/format/webp/lossless/true';
            }

        } elseif ($upload_type == 'audio') {
            $uploadModeAudio = $this->settings->get('upyun.uploadModeAudio');
            $options['allow-file-type'] = 'mp1,mp2,mp3,m4a,ac-3,vorbis,pcm';
            $options['save-key'] = '/' . $folder . '/{filemd5}{.suffix}';
            if ($uploadModeAudio == 'MP3') {
                $options['save-key'] = '/' . $folder . '/{filemd5}.mp3';
                $options['x-audio-avopts'] = '/ab/44/ac/2/f/mp3';
            } elseif ($uploadModeAudio == 'AAC') {
                $options['apps'] = [
                    '0' => [
                        'name' => 'naga',
                        'type' => 'audio',
                        'save_as' => '/' . $folder . '/' . $random . '.m4a',
                        'notify_url' => route('apppsnotify'),
                    ],
                ];
            }
        } elseif ($upload_type == 'video') {
            $uploadModeVideo = $this->settings->get('upyun.uploadModeVideo');
            $uploadModeVideoSize = $this->settings->get('upyun.uploadModeVideoSize');
            $options['allow-file-type'] = 'avi,mp4,flv,mov,3gp,asf,wmv,m3u8,ts,mpg,f4v,m4v,mkv,vob,webm';
            $options['save-key'] = '/' . $folder . '/{filemd5}{.suffix}';

            if ($uploadModeVideo == 'VP9') {
                $vcodec = '/vcodec/libvpx-vp9';
            } elseif ($uploadModeVideo == 'H264') {
                $vcodec = '/vcodec/libx264';
            } elseif ($uploadModeVideo == 'H265') {
                $vcodec = '/vcodec/libx265';
            } else {
                $vcodec = '';
            }

            if ($uploadModeVideoSize != 'ori') {
                $s = '/s/' . $uploadModeVideoSize . 'p(16:9)';
            } else {
                $s = '';
            }

            if ($uploadModeVideo != 'ori' || $uploadModeVideoSize != 'ori') {
                $options['apps'] = [
                    '0' => [
                        'name' => 'naga',
                        'type' => 'video',
                        'avopts' => trim($s . $vcodec),
                        'notify_url' => route('apppsnotify'),
                    ],
                ];
            }

            if ($uploadModeVideo != 'ori') {
                $save_as = '';
                if ($uploadModeVideo == 'VP9') {
                    $save_as = '/' . $folder . '/' . $random . '.webm';
                } elseif ($uploadModeVideo == 'H264' || $uploadModeVideo == 'H265') {
                    $save_as = '/' . $folder . '/' . $random . '.mp4';
                }
                $options['apps'][0] = array_add($options['apps'][0], 'save_as', $save_as);
            }

        } elseif ($upload_type == 'doc') {
            $options['save-key'] = '/' . $folder . '/{filemd5}{.suffix}';
        } elseif ($upload_type == 'file') {
            $options['save-key'] = '/' . $folder . '/{filemd5}{.suffix}';
        } else {
            $options['save-key'] = '/' . $folder . '/{filemd5}{.suffix}';
        }

        $ext = [
            'user_id' => $this->request->user_id,
            'module' => $this->request->module,
            'tag' => $this->request->tag,
            'is_token' => $this->request->is_token,
            'bucket' => $this->bucketName,
            'folder' => $this->folder,
            'ori_filename' => $this->request->ori_filename,
            'driver' => 'upyun',
        ];
        $options['ext-param'] = json_encode($ext);

        return $options;
    }

    public function getUploadInfo()
    {
        $bucket = $this->bucketName;
        $Operator = $this->Operator;
        $Method = 'POST';
        $URI = '/' . $this->bucketName;
        $Password = md5($this->operatorPassword);      //md5 操作员密码

        $options = $this->getOptions();
        $policy = base64_encode(json_encode($options));
        $Signature = base64_encode(hash_hmac('sha1', $Method . '&' . $URI . '&' . $policy, $Password, true));
        $authorization = 'UPYUN ' . $Operator . ':' . $Signature;
        $info = compact("bucket", "policy", "authorization");

        return $info;
    }

    public function getStatus($task_id, $status)
    {
        $bucket = $this->bucketName;
        $Operator = $this->Operator;
        $Password = md5($this->operatorPassword);      //md5 操作员密码
        $Method = 'GET';
        $URI = '/' . $status . '?service=' . $bucket . '&task_ids=' . $task_id;
        $url = 'http://p0.api.upyun.com' . $URI;
        $ch = curl_init($url);
        $Date = gmstrftime("%a, %d %b %Y %T %Z", time());

        $Signature = base64_encode(hash_hmac('sha1', $Method . '&' . $URI . '&' . $Date, $Password, true));
        $authorization = 'UPYUN ' . $Operator . ':' . $Signature;

        $header = [
            "Authorization: $authorization",
            "Date: $Date",
        ];
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);
        curl_close($ch);

        return json_decode($output);
    }

    public function getUploadInfoOld()
    {
        $bucket = $this->bucketName;
        $form_api_secret = $this->formApiSecret; // 表单 API 功能的密匙（请访问又拍云管理后台的空间管理页面获取）
        $options = $this->getOptions();
        $policy = base64_encode(json_encode($options));
        $signature = md5($policy . '&' . $form_api_secret);
        $info = compact("bucket", "policy", "signature");

        return $info;
    }

    /**
     * @param string $name
     *
     * @return bool|mixed
     */
    public function fileList($folder = '/')
    {
        $has = $this->cloud->has($folder);
        if (!$has) {
            return false;
        }
        $data = $this->cloud->read($folder);
        if ($data) {
            return $data;
        } else {
            return false;
        }
    }

    /**
     * @param $path
     *
     * @return mixed
     */
    public function info($path)
    {
        return $this->cloud->info($path);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function delete($path)
    {
        return $this->cloud->delete($path);
    }

    public function deleteById($id)
    {
        $detail = Detail::query()->find($id);
        $this->cloud->delete($detail->path);

        return $detail->delete();
    }

    public function deleteByIds($ids)
    {
        $details = Detail::query()->whereIn('id', $ids)->get()->toArray();
        $listKey = array_pluck($details, 'path');
        foreach ($listKey as $item) {
            $this->cloud->delete($item);
        }

        return Detail::destroy($ids);
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function deleteDir($folder)
    {
        return $this->cloud->deleteDir($folder);
    }

    /**
     * @param string $name
     *
     * @return array|bool
     */
    public function dirDelete($folder = '/')
    {
        $path = $folder ? '/' . $folder . '/' : '/';
        $has = $this->cloud->has($path);
        if (!$has) {
            return false;
        }
        $data = $this->fileList($path);
        if (!$data) {
            return false;
        }
        $filtered = array_filter($data['files'], function ($item) {
            return $item['type'] !== 'F';
        });
        $list_key = array_pluck($filtered, 'name');
        $res = [];
        foreach ($list_key as $item) {
            $res[] = $this->delete($path . $item);
        }
        if ($path != '/') {
            $resdir = $this->cloud->deleteDir($path);

            return $resdir;
        }

        return $res;
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public function download($path)
    {
        $key = explode('/', $path);
        //      $saveLocal = fopen('../public/download/' . $key[2], 'w');
        $saveLocal = fopen('D:/abc/' . $key[2], 'w');
        dd($this->cloud->read($path, $saveLocal));
        if ($this->cloud->read($path, $saveLocal)) {
            return true;
        } else {
            return false;
        }

    }

    public function setIsToken($is_token)
    {
        return $this->is_token = $is_token;
    }

    public function purge($url)
    {
        return $this->cloud->purge($url);
    }

    public function getVideoThumb($path)
    {
        $res = $this->cloud->process($path, [
            [
                'name' => 'naga',
                'type' => 'thumbnail',
                'avopts' => '/o/true/n/1',
                'notify_url' => route('apppsnotify'),
            ],
        ]);

        return $res;
    }

    public function getModule($module, $type = '', $paginate = '')
    {
        $query = Detail::query()->where('module', $module);

        if ($type) {
            $mime = explode(",", $type);
            $query = $query->where('ori_type', 'like', "%$mime[0]%");

            if (count($mime) > 1) {
                for ($i = 1; $i <= count($mime) - 1; $i++) {
                    $query = $query->orWhere('ori_type', 'like', "%$mime[$i]%");
                }
            }
        }

        if ($paginate) {
            $details = $query->paginate($paginate)->toArray();
        } else {
            $details = $query->get()->toArray();
        }

        return $details;
    }

    public function getAll($paginate = '')
    {
        if ($paginate) {
            $details = Detail::paginate($paginate)->toArray();
        } else {
            $details = Detail::all()->toArray();
        }

        return $details;
    }

    public function downloadByids($ids)
    {
        $res = [];
        foreach ($ids as $item) {
            $detail = Detail::find($item);
            $res[] = $this->download($detail->path);
        }

        return $res;
    }

    public function getDetailById($id)
    {
        $detail = Detail::find($id)->toArray();

        return $detail;
    }

}

