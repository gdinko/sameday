<?php

namespace Mchervenkov\Sameday\Hydrators;

use Mchervenkov\Sameday\Actions\HasPagination;

class County
{
    use HasPagination;

    /**
     * Name
     *
     * @var string|null
     */
    private string|null $name;

    /**
     * Set Name and Paginator params
     *
     * @param string|null $name
     */
    public function __construct(string|null $name = null)
    {
        $this->name = $name;
    }
}
