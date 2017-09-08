<?php

/**
 * This file is part of Notadd.
 *
 * @author        linxing <linxing@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      17-6-22 下午6:45
 */
namespace Notadd\Cloud\Controllers;

use Notadd\Cloud\Handlers\GetUpyunconfHandler;
use Notadd\Cloud\Handlers\GetUpyunTokenconfHandler;
use Notadd\Cloud\Handlers\SetUpyunconfHandler;
use Notadd\Cloud\Handlers\SetUpyunTokenconfHandler;
use Notadd\Cloud\Handlers\GetUpyunModeconfHandler;
use Notadd\Cloud\Handlers\SetUpyunModeconfHandler;
use Notadd\Cloud\Handlers\GetUpyunModeAudioHandler;
use Notadd\Cloud\Handlers\SetUpyunModeAudioHandler;
use Notadd\Cloud\Handlers\GetUpyunModeVideoHandler;
use Notadd\Cloud\Handlers\SetUpyunModeVideoHandler;
use Notadd\Cloud\Handlers\SetUpyunWatermarkconfHandler;
use Notadd\Cloud\Handlers\GetUpyunWatermarkconfHandler;
use Notadd\Foundation\Routing\Abstracts\Controller;

class UpyunController extends Controller
{
    /**
     * @param \Notadd\Cloud\Handlers\GetUpyunconfHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function get(GetUpyunconfHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    public function getToken(GetUpyunTokenconfHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * @param \Notadd\Cloud\Handlers\SetUpyunconfHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function set(SetUpyunconfHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    public function setToken(SetUpyunTokenconfHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * @param \Notadd\Cloud\Handlers\GetUpyunModeconfHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function getModeImage(GetUpyunModeconfHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * @param \Notadd\Cloud\Handlers\SetUpyunModeconfHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function setModeImage(SetUpyunModeconfHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    public function getModeAudio(GetUpyunModeAudioHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * @param \Notadd\Cloud\Handlers\SetUpyunModeAudioHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function setModeAudio(SetUpyunModeAudioHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    public function getModeVideo(GetUpyunModeVideoHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * @param \Notadd\Cloud\Handlers\SetUpyunModeVideoHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function setModeVideo(SetUpyunModeVideoHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * @param \Notadd\Cloud\Handlers\SetUpyunWatermarkconfHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function setWatermark(SetUpyunWatermarkconfHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    /**
     * @param \Notadd\Cloud\Handlers\GetUpyunWatermarkconfHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function getWatermark(GetUpyunWatermarkconfHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

//    public function callBack(UpyunReturnHandler $handler)
//    {
//        return $handler->toResponse()->generateHttpResponse();
//    }

}
