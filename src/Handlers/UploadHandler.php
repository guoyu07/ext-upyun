<?php
/**
 * This file is part of Notadd.
 *
 * @author        linxing <linxing@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      17-6-23 下午3:31
 */


namespace Notadd\Cloud\Handlers;

use Notadd\Foundation\Routing\Abstracts\Handler;
use Illuminate\Container\Container;
use Notadd\Cloud\Models\Detail;
use Notadd\Cloud\Models\Group;

class UploadHandler extends Handler
{
    protected $cloud;

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->cloud = $this->container->make('cloud');
    }

    /*
     * Execute Handler
     */
    public function Execute()
    {
        $data = $this->cloud->upload();
        if(!array_key_exists("key",$data)){
            return $this->withCode(402)->withMessage('文件上传失败');
        }else{
            $detail = new Detail();
            $detail->key=$data['key'];
            $detail->driver=$data['driver'];
            $detail->type=$data['mimeType'];
            $detail->size=$data['size'];
            $detail->w=$data['w'];
            $detail->h=$data['h'];
            $detail->hash=$data['hash'];
            $detail->persistentId=$data['persistentId'];
            $detail->exif=$data['exif'];
            $detail->ori_type=$data['mimeType'];
            $detail->group_id=$this->request->get('group_id');
            if($detail->save()){
                if ($data['driver'] == 'upyun') {
                    return $this->withCode(200)->withMessage('成功上传文件到云');
                }
            }else{
                return  $this->withCode(403)->withMessage('数据存表失败');
            }
        }

    }
}