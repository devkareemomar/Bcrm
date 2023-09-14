<?php

namespace Modules\Cms\Repositories\NewsLetters;

use Modules\Cms\Models\NewsLetter;
use App\Repositories\BaseEloquentRepository;

class NewsLetterRepository extends BaseEloquentRepository implements NewsLetterRepositoryInterface
{
    /** @var string */
    protected $modelName = NewsLetter::class;

    protected $searchableFields = ['email'];
}
