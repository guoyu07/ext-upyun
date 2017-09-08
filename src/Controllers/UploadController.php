<?php
/**
 * This file is part of Notadd.
 *
 * @author        linxing <linxing@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      17-6-23 下午3:15
 */
namespace Notadd\Cloud\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Notadd\Cloud\Handlers\VideoThumbNotifyHandler;
use Notadd\Foundation\Routing\Abstracts\Controller;
use Notadd\Cloud\Handlers\ListHandler;
use Notadd\Cloud\Handlers\NotifyHandler;
use Notadd\Cloud\Handlers\AppsNotifyHandler;
use Notadd\Cloud\Handlers\DirDeleteHandler;
use Notadd\Cloud\Handlers\GetUploadInfoHandler;
use Notadd\Cloud\Handlers\GetStatusHandler;
use Notadd\Cloud\Handlers\DeleteHandler;
use Notadd\Cloud\Cloud;
use Notadd\Cloud\Models\Detail;

/**
 * Class UploadController.
 */
class UploadController extends Controller
{
    /**
     * @var \Notadd\Cloud\Cloud
     */
    protected $cloud;

    /**
     * UploadController constructor.
     *
     * @param \Notadd\Cloud\Cloud $cloud
     */
    public function __construct(Cloud $cloud)
    {
        parent::__construct();
        $this->cloud = $cloud;
    }

    /**
     * @param \Notadd\Cloud\Handlers\ListHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function fileList(ListHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * @param \Notadd\Cloud\Handlers\DirDeleteHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function dirDelete(DirDeleteHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    public function Delete(DeleteHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * @return string
     */
    public function test()
    {
        $info = $this->cloud->getUploadInfo();
        $form = '<form method="POST" action="http://v0.api.upyun.com/' . $info['bucket'] . '" enctype="Multipart/form-data">';
        $form .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
        $form .= '<input type="hidden" name="authorization" value="' . $info['authorization'] . '">';
        $form .= '<input type="file" name="file">';
        $form .= '<input type="hidden" name="policy" value="' . $info['policy'] . '">';
        $form .= '<input type="submit" >';
        $form .= '</form>';

        return $form;
    }

    /**
     * @return string
     */
    public function testold()
    {
        $res=$this->cloud->getVideoThumb($this->request->path);
        dd($res);
        $info = $this->cloud->getUploadInfoOld();
        $form = '<form method="post" action="http://v0.api.upyun.com/' . $info['bucket'] . '" enctype="Multipart/form-data">';
        $form .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
        $form .= '<input type="hidden" name="signature" value="' . $info['signature'] . '">';
        $form .= '<input type="file" name="file">';
        $form .= '<input type="hidden" name="policy" value="' . $info['policy'] . '">';
        $form .= '<input type="submit" >';
        $form .= '</form>';

        return $form;

    }

    public function notify(NotifyHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    public function appsNotify(AppsNotifyHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    public function videoThumbNotify(VideoThumbNotifyHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    public function getUploadInfo(GetUploadInfoHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    public function getStatus(GetStatusHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }
}
