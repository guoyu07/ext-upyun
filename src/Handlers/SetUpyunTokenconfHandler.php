<?php

namespace Notadd\Cloud\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Notadd\Foundation\Routing\Abstracts\Handler;

/**
 * Class SetUpyunconfHandler.
 */
class SetUpyunTokenconfHandler extends Handler
{
    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $settings;

    /**
     * SetUpyunTokenconfHandler constructor.
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
            'domain'           => 'required',
            'token'            => 'required|alpha_num',
            'tokenTime'        => 'integer',
            'folder'        => 'required',
        ], [
            'operatorName.required'        => 'operatorName不能为空',
            'operatorName.alpha_dash'      => 'operatorName由字母数字下划线组成',
            'operatorPassword.required'    => 'operatorPassword不能为空',
            'operatorPassword.ralpha_dash' => 'operatorPassword由数字字母下滑线组成',
            'bucketName.required'          => 'bucketName不能为空',
            'bucketName.alpha_dash'        => 'bucketName由数字字母下滑线组成',
            'domain.required'              => '域名不能为空',
            'token.alpha_num'              => 'token由字母数字组成',
        ]);
        $this->settings->set('upyun.private.bucketName', $this->request->input('bucketName'));
        $this->settings->set('upyun.private.operatorName', $this->request->input('operatorName'));
        $this->settings->set('upyun.private.operatorPassword', $this->request->input('operatorPassword'));
        $this->settings->set('upyun.private.domain', $this->request->input('domain'));
        $this->settings->set('upyun.private.folder', $this->request->input('folder', 'notadd'));
        $this->settings->set('upyun.private.token', $this->request->input('token'));
        $this->settings->set('upyun.private.tokenTime', $this->request->input('tokenTime', 600));
        $tokenEnabled=($this->settings->get('upyun.private.token'))?true:false;
        $this->settings->set('upyun.private.tokenEnabled', $tokenEnabled);

        $allData = $this->settings->all()->toArray();
        $data = array_only($allData, [
            'upyun.private.bucketName',
            'upyun.private.operatorName',
            'upyun.private.operatorPassword',
            'upyun.private.domain',
            'upyun.private.folder',
            'upyun.private.token',
            'upyun.private.tokenTime',
            'upyun.private.tokenEnabled',
        ]);
        if ($data) {
            $this->withCode(200)->withData($data)->withMessage('修改设置成功!');
        } else {
            $this->withCode(200)->withMessage('修改设置失败!');
        }
    }

}

