<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 30/09/2018
 * Time: 4:02 PM
 */

namespace Lvinkim\SwimKernel\Component;


class ActionResponse
{
    /**
     * ActionResponse constructor.
     * @param string $body
     * @param bool $sent
     */
    public function __construct(string $body = "{}", bool $sent = false)
    {
        $this->setBody($body);
        $this->setSent($sent);
    }

    /**
     * 是否已在 Action 类中返回响应
     * @var bool
     */
    private $sent = false;

    /**
     * header 的 content type
     * @var string
     */
    private $contentType = "application/json;charset=utf-8";

    /**
     * http 报文 body
     * @var string
     */
    private $body = "{}";

    /**
     * @return bool
     */
    public function isSent(): bool
    {
        return $this->sent;
    }

    /**
     * @param bool $sent
     */
    public function setSent(bool $sent): void
    {
        $this->sent = $sent;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     */
    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }
}