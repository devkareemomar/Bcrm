<?php

namespace Modules\Cms\Repositories\Media;

use Modules\Cms\Models\Media;
use App\Repositories\BaseEloquentRepository;

class MediaRepository extends BaseEloquentRepository implements MediaRepositoryInterface
{
    /** @var string */
    protected $modelName = Media::class;

    protected $filterableFields = [];
    protected $searchableFields = ['name', 'title', 'alt'];
}
