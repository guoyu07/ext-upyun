<?php

namespace Notadd\Cloud\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Notadd\Foundation\Routing\Abstracts\Handler;

/**
 * Class SetUpyunconfHandler.
 */
class SetUpyunconfHandler extends Handler
{
    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $settings;

    /**
     * SetUpyunconfHandler constructor.
     *
     * @param \Illuminate\Container\Container                         $container
     * @param \Notadd\Foundation\Setting\Contracts\SettingsRepository $settings
     */
    public function __construct(Container $container, SettingsRepository $settings)
    {
        parent::__construct($container);
        $this->settings = $settings;
    }

    public function execute()
    {
        $this->validate($this->request, [
            'bucketName'       => 'required|alpha_dash',
            'operatorName'     => 'required|alpha_dash',
            'operatorPassword' => 'required|alpha_dash',
            'domain'           => 'required|url',
            'folder'           => 'required',
        ], [
            'operatorName.required'        => 'operatorName不能为空',
            'operatorName.alpha_dash'      => 'operatorName由字母数字下划线组成',
            'operatorPassword.required'    => 'operatorPassword不能为空',
            'operatorPassword.ralpha_dash' => 'operatorPassword由数字字母下滑线组成',
            'bucketName.required'          => 'bucketName不能为空',
            'bucketName.alpha_dash'        => 'bucketName由数字字母下滑线组成',
            'domain.required'              => '域名不能为空',
            'domain.url'                   => '域名必须为合法路径',
            'folder.required'              => '文件夹名字不能为空'
        ]);
        $this->settings->set('upyun.bucketName', $this->request->input('bucketName'));
        $this->settings->set('upyun.operatorName', $this->request->input('operatorName'));
        $this->settings->set('upyun.operatorPassword', $this->request->input('operatorPassword'));
        $this->settings->set('upyun.domain', $this->request->input('domain'));
        $this->settings->set('upyun.folder', $this->request->input('folder'));

        $allData = $data = $this->settings->all()->toArray();
        $data = array_only($allData, [
            'upyun.bucketName',
            'upyun.operatorName',
            'upyun.operatorPassword',
            'upyun.domain',
            'upyun.folder',
        ]);
        if ($data) {
            $this->withCode(200)->withData($data)->withMessage('修改设置成功!');
        } else {
            $this->withCode(200)->withError('修改设置失败!');
        }

    }

}

