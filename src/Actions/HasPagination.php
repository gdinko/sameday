<?php

namespace Mchervenkov\Sameday\Actions;

use Mchervenkov\Sameday\Hydrators\Paginator;

trait HasPagination
{
    /**
     * Get Pagination Params
     *
     * @param Paginator|null $paginator
     * @return array
     */
    public function getPaginationData(Paginator $paginator = null): array
    {
        $paginationData = [];

        if($paginator) {
            $paginationData = [
                'page' => $paginator->page,
                'countPerPage' => $paginator->countPerPage,
            ];
        }

        return $paginationData;
    }
}
