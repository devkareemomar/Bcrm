<?php

namespace Modules\Cms\Repositories\Pages;

use Modules\Cms\Models\Page;
use App\Repositories\BaseEloquentRepository;

class PageRepository extends BaseEloquentRepository implements PageRepositoryInterface
{
    /** @var string */
    protected $modelName = Page::class;

    protected $filterableFields = ['category_id', 'type'];
    protected $searchableFields = ['title_ar', 'title_en'];
}
