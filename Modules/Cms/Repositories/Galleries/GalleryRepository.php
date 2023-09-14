<?php

namespace Modules\Cms\Repositories\Galleries;

use Modules\Cms\Models\Gallery;
use App\Repositories\BaseEloquentRepository;

class GalleryRepository extends BaseEloquentRepository implements GalleryRepositoryInterface
{
    /** @var string */
    protected $modelName = Gallery::class;

    protected $filterableFields = ['category_id', 'title'];
    protected $searchableFields = ['title_ar', 'title_en'];
}
