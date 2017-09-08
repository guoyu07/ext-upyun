<?php
/**
 * This file is part of Notadd.
 *
 * @author        aen233<zhanghe@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-08-04 22:49
 */
namespace Notadd\Cloud\Handlers;

use Notadd\Foundation\Routing\Abstracts\Handler;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Log;
use Notadd\Cloud\Cloud;
use Illuminate\Support\Facades\Request;
use Notadd\Cloud\Models\Detail;

/**
 * Class NotifyHandler.
 */
class AppsNotifyHandler extends Handler
{
    /**
     * @var \Notadd\Cloud\Cloud
     */
    protected $cloud;

    /**
     * AppsNotifyHandler constructor.
     *
     * @param \Illuminate\Container\Container $container
     * @param \Notadd\Cloud\Cloud             $cloud
     */
    public function __construct(Container $container, Cloud $cloud)
    {
        parent::__construct($container);
        $this->cloud = $cloud;
    }

    public function execute()
    {
        $data = Request::all();
        log::info($data);
        $detail = Detail::where('task_id', $data['task_id'])->first();
        log::info($detail->path);
        $path = $detail->path;

        $is_token = $detail->is_token;
        $this->cloud->setIsToken($is_token);
        $this->cloud->delete($path);
        $detail->path = $data['path'][0];
        $suffix = strstr($data['path'][0], '.');
        log::info($suffix);
        if ($suffix == '.m4a') {
            $detail->type = 'AAC';
        } elseif ($suffix == '.mp4') {
            $detail->type = 'H264/H265';
        } elseif ($suffix == '.webm') {
            $detail->type = 'VP9';
        }

        if ($suffix == '.mp4' || $suffix == '.webm') {
            $thumbTask = $this->cloud->getVideoThumb($data['url']);
            $detail->task_id = $thumbTask[0];
        }

        $info = $this->cloud->info($data['path'][0]);
        log::info($info);
        $detail->size = $info['x-upyun-file-size'];

        if ($detail->save()) {
            log::info('modify data success');
        } else {
            log::error('modify data fail');
        }

    }
}
