<?php

namespace Modules\Cms\Repositories\Posts;

use Modules\Cms\Models\Comment;
use App\Repositories\BaseEloquentRepository;

class CommentRepository extends BaseEloquentRepository implements CommentRepositoryInterface
{
    /** @var string */
    protected $modelName = Comment::class;

    protected $filterableFields = ['post_id'];
}
