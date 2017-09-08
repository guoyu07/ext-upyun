<?php
/**
 * This file is part of Notadd.
 *
 * @author        linxing <linxing@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      17-6-23 上午10:25
 */

namespace Notadd\Cloud\Listeners;

use Notadd\Cloud\Controllers\UpyunController;
use Notadd\Cloud\Controllers\UploadController;
use Notadd\Cloud\Controllers\QueryController;

use Notadd\Foundation\Routing\Abstracts\RouteRegister as AbstractRouteRegister;

/**
 * Class RouteRegister.
 */
class RouteRegister extends AbstractRouteRegister
{
    /**
     * Handle Route Registrar.
     */
    public function handle()
    {

        $this->router->group(['middleware' => ['cross', 'web'], 'prefix' => 'api/'], function () {

            $this->router->post('upload/info/get', UploadController::class . '@getUploadInfo');
        });

        $this->router->group(['middleware' => ['cross', 'web'], 'prefix' => 'api/cloud'], function () {

            $this->router->post('search', QueryController::class . '@search');
            $this->router->post('groups', QueryController::class . '@groups');
            $this->router->post('details', QueryController::class . '@details');
            $this->router->post('group/create', QueryController::class . '@newGroup');
            $this->router->post('group/delete', QueryController::class . '@deleteGroup');
            $this->router->post('all/delete', QueryController::class . '@deleteAll');

            $this->router->post('delete', UploadController::class . '@delete');
            $this->router->post('upload', UploadController::class . '@upload');
            $this->router->post('list', UploadController::class . '@fileList');
            $this->router->post('dir/delete', UploadController::class . '@dirDelete');
            $this->router->post('test', UploadController::class . '@test');
            $this->router->post('test/old', UploadController::class . '@testold');
            $this->router->get('status/get', UploadController::class . '@getstatus');


            $this->router->post('notify', UploadController::class . '@notify')->name('notify');
            $this->router->post('notify/apps', UploadController::class . '@appsNotify')->name('apppsnotify');
            $this->router->post('notify/video/thumb', UploadController::class . '@videoThumbNotify')->name('videoThumbNotify');

            $this->router->post('set', UpyunController::class . '@set');
            $this->router->post('get', UpyunController::class . '@get');
            $this->router->post('private/set', UpyunController::class . '@setPrivate');
            $this->router->post('private/get', UpyunController::class . '@getPrivate');
            $this->router->post('watermark/set', UpyunController::class . '@setWatermark');
            $this->router->post('watermark/get', UpyunController::class . '@getWatermark');

            $this->router->post('mode/image/set', UpyunController::class . '@setModeImage');
            $this->router->post('mode/image/get', UpyunController::class . '@getmodeImage');
            $this->router->post('mode/audio/set', UpyunController::class . '@setModeAudio');
            $this->router->post('mode/audio/get', UpyunController::class . '@getmodeAudio');
            $this->router->post('mode/video/set', UpyunController::class . '@setModeVideo');
            $this->router->post('mode/video/get', UpyunController::class . '@getmodeVideo');

        });

    }
}
