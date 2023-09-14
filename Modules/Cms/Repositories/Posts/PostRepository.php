<?php

namespace Modules\Cms\Repositories\Posts;

use Modules\Cms\Models\Post;
use App\Repositories\BaseEloquentRepository;

class PostRepository extends BaseEloquentRepository implements PostRepositoryInterface
{
    /** @var string */
    protected $modelName = Post::class;

    protected $filterableFields = ['category_id', 'type', 'is_active'];
    protected $searchableFields = ['title_ar', 'title_en'];
}
