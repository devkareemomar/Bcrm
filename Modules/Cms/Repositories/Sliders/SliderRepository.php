<?php

namespace Modules\Cms\Repositories\Sliders;


use Modules\Cms\Models\Slider;
use App\Repositories\BaseEloquentRepository;

class SliderRepository extends BaseEloquentRepository implements SliderRepositoryInterface
{
    /** @var string */
    protected $modelName = Slider::class;

    protected $filterableFields = ['category_id'];
    protected $searchableFields = ['title_ar', 'title_en'];
}
