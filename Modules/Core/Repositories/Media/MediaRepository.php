<?php

namespace Modules\Core\Repositories\Media;

use Modules\Core\Models\Media;
use App\Repositories\BaseEloquentRepository;

class MediaRepository extends BaseEloquentRepository implements MediaRepositoryInterface
{
    /** @var string */
    protected $modelName = Media::class;

    protected $filterableFields = [];
    protected $searchableFields = [];
}
