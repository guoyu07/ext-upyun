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
use Notadd\Cloud\Models\Detail;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Log;
use Notadd\Cloud\Cloud;
use Illuminate\Support\Facades\Request;

/**
 * Class NotifyHandler.
 */
class NotifyHandler extends Handler
{
    /**
     * @var \Notadd\Cloud\Cloud
     */
    protected $cloud;

    /**
     * NotifyHandler constructor.
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

        $ext = json_decode($data['ext-param']);

        $detail = new Detail();
        $detail->path = $data['url'];
        $detail->ori_type = $data['mimetype'];

        if (starts_with($data['mimetype'], 'image')) {
            $detail->width = $data['image-width'];
            $detail->height = $data['image-height'];
            $detail->type = $data['image-type'];
        }

        $suffix = strstr($data['url'], '.');
        if ($suffix == '.mp3') {
            $detail->type = 'MP3';
        } elseif ($suffix == '.mp4') {
            $detail->type = 'H264/H265';
        } elseif ($suffix == '.webm') {
            $detail->type = 'VP9';
        }

        $detail->size = $data['file_size'];
        $detail->user_id = $ext->user_id;
        $detail->is_token = $ext->is_token;
        $detail->module = $ext->module;
        $detail->tag = $ext->tag;
        $detail->bucket = $ext->bucket;
        $detail->folder = $ext->folder;
        $detail->driver = $ext->driver;
        $detail->ori_filename = $ext->ori_filename;

        if (array_key_exists('task_ids', $data)) {
            $detail->task_id = $data['task_ids'];
        }

        if (starts_with($data['mimetype'], 'video') && !array_key_exists('task_ids', $data)) {
            $thumbTask = $this->cloud->getVideoThumb($data['url']);
            $detail->task_id = $thumbTask[0];
        }

        $beforeDot = strstr($data['url'], '.', true);
        $md5 = explode('/', $beforeDot);
        $detail->md5 = $md5[2];

        if ($detail->save()) {
            log::info('save data success');
        } else {
            log::error('save data fail');
        }

    }
}
