<?php

/**
 * Page
 */
class Page
{
    /**
     * 当前页
     * @var int
     */
    public $current;

    /**
     * 每页记录数量
     * @var int
     */
    public $per;

    /**
     * 总页数
     * @var int
     */
    public $total;

    /**
     * 总记录数
     * @var int
     */
    public $totalCount;

    public function __construct($current, $per = null, $total = null, $totalCount = null)
    {
        $this->current = $current;
        $this->per = $per;
        $this->total = $total;
        $this->totalCount = $totalCount;
    }
}
