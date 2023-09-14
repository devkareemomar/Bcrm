<?php

namespace Modules\Crm\Repositories\Activities;

use Modules\Crm\Models\Activity;
use App\Repositories\BaseEloquentRepository;
use Modules\Crm\Http\Resources\Activities\ActivityCollection;

class ActivityRepository extends BaseEloquentRepository implements ActivityRepositoryInterface
{
    /** @var string */
    protected $modelName = Activity::class;

    protected $filterableFields = [];
    protected $searchableFields = ['code'];

    public function maxId()
    {
        $instance = $this->getQueryBuilder();
        $id = $instance->max('id');
        return $id ? $id + 1 : 1;
    }

    public function bulkStore($data,$branchId)
    {
        // TOFIX:
        $id =$this->maxId();

        $data = collect($data)->map(function ($row, $key) use ($id,$branchId) {
            $row['code'] = 'activity_' . $id + $key;
            $row['created_by_id'] = auth()->user()->id;
            $row['branch_id'] = $branchId;
            $row['activitable_type'] = 'Modules\Crm\Models\\' .$row['activitable_type'];
            return $row;
        })->toArray();
        $this->insert($data);
    }

    public function getAllByDate($request, $branchId)
    {
        $activitable_type = 'Modules\Crm\Models\\' . ucfirst($request->activitable_type);

        $parameters =[
            'branch_id' => $branchId,
            'activitable_type' => $activitable_type,
            'activitable_id' => $request->activitable_id,
        ];

        if($request->type){

            $parameters = ['type' => $request->type] + $parameters;
        }

        if($request->date){

            $parameters = ['reminder_date' => $request->date] + $parameters;
        }

        $activities =  $this->paginate(
                'id',
                ['assignTo'],
                paginate: $request->limit ?? 15,
                parameters: $parameters,
            );

        return new ActivityCollection($activities);


    }


}
