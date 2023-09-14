<?php

namespace Modules\Crm\Repositories\Leads;

use App\Exports\BaseExampleExport;
use App\Exports\BaseExport;
use App\Imports\BaseImport;
use Modules\Crm\Models\Lead;
use App\Repositories\BaseEloquentRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\Http\Resources\ActivityLogs\ActivityLogCollection;
use Modules\Crm\Repositories\Clients\ClientRepositoryInterface;
use Modules\Crm\Repositories\Contacts\ContactRepositoryInterface;
use Modules\Crm\Repositories\LeadStages\LeadStageRepositoryInterface;
use Modules\Crm\Repositories\Sources\SourceRepositoryInterface;
use Spatie\Activitylog\Models\Activity;

class LeadRepository extends BaseEloquentRepository implements LeadRepositoryInterface
{
    /** @var string */
    protected $modelName = Lead::class;

    protected $filterableFields = ['branch_id', 'source_id', 'stage_id', 'assign_to_id', 'type', 'class_id'];
    protected $searchableFields = ['code', 'first_name', 'last_name', 'phone', 'email'];


    /**
     * Create a new record
     *
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */
    public function store(array $data)
    {
        $this->instance = $this->getNewInstance();

        DB::beginTransaction();
        try {
            $data['branch_id'] = request()->route('branch');

            //TOFIX:
            $id = $this->maxId();
            $data['code'] = 'lead_' . $id;

            $orderBy = $this->orderBy($data['stage_id']);
            $data['order_by'] = $orderBy;

            $lead = $this->executeSave(Arr::except($data, 'documents'));

            if (isset($data['documents'])) {
                $leadDocuments = collect($data['documents'])->mapWithKeys(function ($item) {
                    return [$item['document_media_id'] => ['expire_date' => $item['expire_date']]];
                })->toArray();

                $lead->documents()->sync($leadDocuments);
            }


            // commit changes
            DB::commit();
            return $lead;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    /**
     * upate a  record
     *
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */
    public function updateByInstance($lead,$data)
    {
        $this->instance = $lead;

        DB::beginTransaction();
        try {

            $lead = $this->executeSave(Arr::except($data, 'documents'));

            if (isset($data['documents'])) {
                $leadDocuments = collect($data['documents'])->mapWithKeys(function ($item) {
                    return [$item['document_media_id'] => ['expire_date' => $item['expire_date']]];
                })->toArray();

                $lead->documents()->sync($leadDocuments);
            }


            // commit changes
            DB::commit();
            return $lead;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function maxId()
    {
        $instance = $this->getQueryBuilder();
        $id = $instance->max('id');
        return $id ? $id + 1 : 1;
    }

    private function orderBy($stage_id)
    {
        $instance = $this->getQueryBuilder();
        $id = $instance->where('stage_id', $stage_id)->max('order_by');
        return $id ? $id + 1 : 1;
    }

    /**
     * download example file excel
     */
    public function downloadExampleExcel()
    {
        $attributes = $this->getTableColumns(except: ['id', 'code', 'branch_id', 'created_at', 'updated_at']);

        $name = 'leads-exampel-' . random_int(100000, 999999) . '.xlsx';

        return Excel::download(new BaseExampleExport($attributes), $name);
    }

    /**
     * import leads from file excel
     * @param array request
     * @return array branchId
     */
    public function importData($request, $branchId)
    {
        try {

            // TOFIX:
            $pirfix = 'lead_';
            $code = $this->maxId();

            Excel::import(new BaseImport($this->modelName, $branchId, $pirfix, $code), $request->file);

            return response()->json([
                'success' => '200',
                'message' => 'Leads are successfully imported.'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'error' => '404',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * download leads into file excel
     * @param array request
     * @return array branchId
     */
    public function downloadExport($request, $branchId)
    {
        $fields = ($request->fields) ? $request->fields : $this->getTableColumns(except: ['id', 'created_at', 'updated_at']);

        $leadsCollection  = $this->getAll(
            relations: ['branch', 'stage', 'source', 'department', 'assign', 'team', 'photo', 'class'],
            parameters: ['branch_id' => $branchId],
            fields: $fields ?? ['*']
        );

        $name = 'leads-' . random_int(100000, 999999) . '.xlsx';

        $leads = $this->reMappingCollection($leadsCollection);


        return Excel::download(new BaseExport($leads, $fields), $name);
    }

    /**
     * return leads collection after custom relationships
     * @param array leadsCollection
     */
    protected function reMappingCollection($leadsCollection)
    {
        $data =  $leadsCollection->toArray();
        foreach ($leadsCollection as $key =>  $lead) {
            $data[$key]['branch_id'] = $lead->branch->name ?? '';
            $data[$key]['stage_id'] = $lead->stage->name ?? '';
            $data[$key]['source_id'] = $lead->source->name ?? '';
            $data[$key]['team_id'] = $lead->team->name ?? '';
            $data[$key]['class_id'] = $lead->class->name ?? '';
            $data[$key]['department_id'] = $lead->department->name ?? '';
            $data[$key]['assign_to_id'] = $lead->assign->name ?? '';
            $data[$key]['photo_id']   = isset($lead->photo) ? asset(Storage::url($lead->photo->file)) : null;
            $data[$key] = Arr::except($data[$key], ['branch', 'stage', 'source', 'department', 'assign', 'team', 'photo', 'class']);
        }

        return collect($data);
    }

    /**
     * return Statistics leads by  Stage
     * @param int branchId
     */
    public function statisticsStage($branchId)
    {
        return  app()->make(LeadStageRepositoryInterface::class)->getAll(relationCountWhere: 'leads', condition: ['branch_id' => $branchId], fields: ['id', 'name']);
    }

    /**
     * return Statistics leads by  Source
     * @param int branchId
     */
    public function statisticsSource($branchId)
    {
        return  app()->make(SourceRepositoryInterface::class)->getAll(relationCountWhere: 'leads', condition: ['branch_id' => $branchId], fields: ['id', 'name']);
    }


    /**
     * return Statistics leads by  Company and Individual
     * @param int branchId
     */
    public function statisticsLeadType($branchId)
    {
        $countLeadCompany = $this->count(parameters: ['branch_id' => $branchId, 'type' => 'company']);
        $countLeadIndividual = $this->count(parameters: ['branch_id' => $branchId, 'type' => 'individual']);

        return [
            'company' => $countLeadCompany,
            'individual' => $countLeadIndividual
        ];
    }

    /**
     * return  leads by  Stage
     * @param int branchId
     */
    public function leadsByStage($branchId)
    {
        return  app()->make(LeadStageRepositoryInterface::class)->getAll(orderBy: 'order_by', relationWhere: 'leads', condition: ['branch_id' => $branchId], fields: ['id', 'name']);
    }



    /**
     * convert lead to client
     * @param string branchId & id
     * @return string client_id
     */
    public function ConvertToClient($branchId, $id)
    {

        DB::beginTransaction();
        try {

            $leadData = $this->findById(
                $id,
                fields: [
                    'company_name', 'first_name', 'last_name', 'phone', 'mobile',
                    'email', 'address', 'location', 'city', 'country', 'website',
                    'facebook', 'linkedin', 'industry', 'longitude', 'latitude',
                    'type', 'photo_id', 'branch_id', 'source_id', 'class_id', 'is_client'
                ]
            );

            if (!$leadData) {
                return response()->json([
                    'error' => '404',
                    'message' => 'resource not found.'
                ], 404);
            }

            if ($leadData->is_client == 1) {
                return response()->json([
                    'error' => '201',
                    'message' => 'this lead already converted to client'
                ], 201);
            }

            // update lead type to is client
            $this->updateById($id, ['is_client' => 1]);

            // convert lead to client
            $clientSaved = $this->convertClient($leadData);

            //  convert contact lead to contact client
            $this->convertContact($id, $clientSaved);

            // commit changes
            DB::commit();
            return response()->json([
                'error' => '200',
                'message' => 'Lead converted successfully',
                'result' => ['client_id' => $clientSaved->id]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => '404',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * convert contact from lead to client
     * @param string id & clientSaved
     * @return string saved contact
     */
    private function convertContact($id, $clientSaved)
    {
        $instance = app()->make(ContactRepositoryInterface::class);

        $contact =  $instance->findBy('lead_id', $id);

        if ($contact) {
            app()->make(ContactRepositoryInterface::class)->update(['lead_id' => $id], ['client_id' => $clientSaved->id]);
        } else {
            // TOFIX:
            $id = $instance->maxId();
            $data['code'] = 'Contact_' . $id;

            $contact = [
                'code'             => 'Contact_' . $id,
                'branch_id'        => $clientSaved['branch_id'],
                'client_id'        => $clientSaved->id,
                'first_name'       => $clientSaved['first_name'],
                'last_name'        => $clientSaved['last_name'],
                'email'            => $clientSaved['contact_email'],
                'phone'            => $clientSaved['contact_phone'],
            ];
            $instance->store($contact);
        }
    }

    private function convertClient($leadData)
    {
        $clientRepository = app()->make(ClientRepositoryInterface::class);


        // TOFIX:
        $pirfix = 'client_';
        $code = $pirfix . $clientRepository->maxId();


        $data = ['code' => $code] + $leadData->toArray();
        return $clientRepository->store(Arr::except($data, 'is_client'));
    }


    /**
     * return lead logs
     * @param string request
     * @return string array
     */
    public function getLeadLogs($request)
    {
        $activitylogs = Activity::where(['log_name' => 'Lead']);

        $activitylogs->where('properties->attributes->lead_id', $request->lead_id);
        $activitylogs->orWhere('properties->attributes->id', $request->lead_id);
        $activitylogs->orWhere('properties->attributes->activitable_id', $request->lead_id);
        $activitylogs->orWhere('properties->old->activitable_id', $request->lead_id);

        $activitylogs = $activitylogs->orderBy('id', 'desc')->paginate($request->limit ?? 15);

        return new ActivityLogCollection($activitylogs);
    }
}
