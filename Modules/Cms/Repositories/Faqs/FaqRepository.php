<?php

namespace Modules\Cms\Repositories\Faqs;

use Modules\Cms\Models\Faq;
use App\Repositories\BaseEloquentRepository;

class FaqRepository extends BaseEloquentRepository implements FaqRepositoryInterface
{
    /** @var string */
    protected $modelName = Faq::class;

    protected $searchableFields = ['question_ar', 'question_en'];
}
