<?php
namespace Notadd\Cloud\Handlers;

use Illuminate\Container\Container;
use Notadd\Cloud\Cloud;
use Notadd\Foundation\Routing\Abstracts\Handler;

/**
 * Class GetUploadInfoHandler.
 */
class GetUploadInfoHandler extends Handler
{
    /**
     * @var \Notadd\Cloud\Cloud
     */
    protected $cloud;

    /**
     * GetUploadInfoHandler constructor.
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
        $this->validate($this->request, [
            'user_id'  => 'required|integer',
            'module'   => 'required',
            'tag'      => 'required',
            'is_token' => 'required|boolean',
            'upload_type' => 'required|in:image,audio,video,doc,file',
            'ori_filename' => 'required'
        ], [
            'user_id.required' => '用户ID不能为空',
        ]);
        $data = $this->cloud->getUploadInfo();
        if ($data) {
            return $this->withCode(200)->withData($data)->withMessage('获取信息成功');
        } else {
            return $this->withCode(201)->withError('获取信息失败');
        }
    }
}
