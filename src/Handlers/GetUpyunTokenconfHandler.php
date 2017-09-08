<?php
namespace Notadd\Cloud\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

/**
 * Class GetUpyunTokenconfHandler.
 */
class GetUpyunTokenconfHandler extends Handler
{
    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $settings;

    /**
     * GetUpyunTokenconfHandler constructor.
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
        $data = [
            'bucketName'       => $this->settings->get('upyun.private.bucketName'),
            'operatorName'     => $this->settings->get('upyun.private.operatorName'),
            'operatorPassword' => $this->settings->get('upyun.private.operatorPassword'),
            'domain'           => $this->settings->get('upyun.private.domain'),
            'folder'           => $this->settings->get('upyun.private.folder'),
            'token'             => $this->settings->get('upyun.private.token'),
            'tokenTime'         => $this->settings->get('upyun.private.tokenTime'),
            'tokenEnabled'      => $this->settings->get('upyun.private.tokenEnabled'),
        ];
        $this->withCode(200)->withData($data)->withMessage('获取私有云空间信息成功!');
    }
}
