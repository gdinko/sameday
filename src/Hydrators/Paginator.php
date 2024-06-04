<?php

namespace Mchervenkov\Sameday\Hydrators;

class Paginator
{
    /**
     * Display Page (default 1)
     *
     * @var int
     */
    public int $page;

    /**
     * Display results per page (default 500)
     *
     * @var int
     */
    public int $countPerPage;

    /**
     * Set Page and CountPerPage properties
     *
     * @param int $page
     * @param int $countPerPage
     */
    public function __construct(int $page = 1, int $countPerPage = 500)
    {
        $this->page = $page;
        $this->countPerPage = $countPerPage;
    }

    /**
     * @param int $page
     * @return void
     */
    public function setPage(int $page)
    {
        $this->page = $page;
    }

    /**
     * @param int $countPerPage
     * @return void
     */
    public function setCountPerPage(int $countPerPage)
    {
        $this->countPerPage = $countPerPage;
    }
}
