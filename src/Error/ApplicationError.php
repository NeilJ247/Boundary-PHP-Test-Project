<?php

namespace BoundaryWS\Error;

use Ramsey\Uuid\Uuid;

class ApplicationError
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $errorCode;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $detail;

    /**
     * @var array
     */
    protected $source;

    /**
     * ApplicationError constructor.
     *
     * @param string $errorCode      Application error code
     * @param string $title          Error title
     * @param string $detail         Error detail (message)
     * @param int    $status         Exception code equivalent to HTTP response status code
     * @param array  $source         Source, if any
     */
    public function __construct(
        string $errorCode,
        string $title,
        string $detail,
        int $status,
        array $source = null
    ) {
        $this->id = $this->generateId();
        $this->errorCode = $errorCode;
        $this->title = $title;
        $this->detail = $detail;
        $this->status = $status;
        $this->source = $source;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function generateId(): string
    {
        return str_replace('-', '', Uuid::uuid4()->toString());
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDetail(): string
    {
        return $this->detail;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getSource()
    {
        return $this->source;
    }

    public function toArray(): array
    {
        $array = [
            'id'     => $this->id,
            'status' => $this->status,
            'code'   => $this->errorCode,
            'title'  => $this->title,
            'detail' => $this->detail,
        ];

        if ($this->source) {
            $array['source'] = $this->source;
        }

        return $array;
    }
}
