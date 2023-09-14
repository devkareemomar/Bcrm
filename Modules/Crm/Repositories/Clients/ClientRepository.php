<?php

namespace Modules\Crm\Repositories\Clients;

use App\Exports\BaseExport;
use App\Imports\BaseImport;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Crm\Models\Client;
use App\Exports\BaseExampleExport;
use App\Repositories\BaseEloquentRepository;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\Repositories\Branches\BranchRepositoryInterface;
use Modules\Crm\Repositories\Contacts\ContactRepositoryInterface;
use Modules\Crm\Repositories\Sources\SourceRepositoryInterface;

class ClientRepository extends BaseEloquentRepository implements ClientRepositoryInterface
{
    /** @var string */
    protected $modelName = Client::class;

    protected $filterableFields = ['branch_id', 'source_id','type','class_id'];
    protected $searchableFields = ['code','first_name', 'last_name', 'email', 'phone',];


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
            $data['code'] = 'client_' . $id;

            $client = $this->executeSave(Arr::except($data, [ 'documents','contact_email','contact_phone','contact_position']));

            if(isset($data['contact_email']) || isset($data['contact_phone']) || isset($data['contact_position'])){
                $this->addContact($data,$client->id);
            }

            if(isset($data['documents'])){

                $clientDocuments = collect($data['documents'])->mapWithKeys(function ($item) {
                    return [$item['document_media_id'] => ['expire_date' => $item['expire_date']]];
                })->toArray();

                $client->documents()->sync($clientDocuments);
            }
            // commit changes
            DB::commit();
            return $client;
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
    public function updateByInstance($client,$data)
    {
        $this->instance = $client;

        DB::beginTransaction();
        try {

            $client = $this->executeSave(Arr::except($data, [ 'documents','contact_email','contact_phone','contact_position']));
            $data['branch_id'] = $client->branch_id;
            if(isset($data['contact_email']) || isset($data['contact_phone']) || isset($data['contact_position'])){
                $this->addContact($data,$client->id);
            }

            if(isset($data['documents'])){
                $clientDocuments = collect($data['documents'])->mapWithKeys(function ($item) {
                    return [$item['document_media_id'] => ['expire_date' => $item['expire_date']]];
                })->toArray();

                $client->documents()->sync($clientDocuments);
            }
            // commit changes
            DB::commit();
            return $client;
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


    /**
     * download example file excel
     */
    public function downloadExampleExcel()
    {
        $attributes = $this->getTableColumns(except: ['id', 'code', 'branch_id', 'created_at', 'updated_at']);

        $name = 'clients-exampel-' . random_int(100000, 999999) . '.xlsx';

        return Excel::download(new BaseExampleExport($attributes), $name);
    }

    /**
     * import clients from file excel
     * @param array request
     * @return array branchId
     */
    public function importData($request, $branchId)
    {
        try {

            // TOFIX:
            $pirfix = 'client_';
            $code = $this->maxId();

            Excel::import(new BaseImport($this->modelName, $branchId, $pirfix, $code), $request->file);

            return response()->json([
                'success' => '200',
                'message' => 'Clients are successfully imported.'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'error' => '404',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * download clients into file excel
     * @param array request
     * @return array branchId
     */
    public function downloadExport($request, $branchId)
    {
        $fields = ($request->fields) ? $request->fields :$this->getTableColumns(except: ['id', 'created_at', 'updated_at']);

        $clientsCollection  = $this->getAll(
            relations: ['branch','source', 'class','photo'],
            parameters: ['branch_id' => $branchId],
            fields: $fields ?? ['*']
        );

        $name = 'clients-' . random_int(100000, 999999) . '.xlsx';

        $clients = $this->reMappingCollection($clientsCollection);


        return Excel::download(new BaseExport($clients, $fields), $name);
    }

    /**
     * return clients collection after custom relationships
     * @param array clientsCollection
     */
    protected function reMappingCollection($clientsCollection)
    {
        $data =  $clientsCollection->toArray();
        foreach ($clientsCollection as $key =>  $client) {
            $data[$key]['branch_id'] = $client->branch->name ?? '';
            $data[$key]['source_id'] = $client->source->name ?? '';
            $data[$key]['class_id']  = $client->class->name ?? '';
            $data[$key]['photo_id']  = isset($client->photo) ? asset(Storage::url($client->photo->file)) : null;
            $data[$key] = Arr::except($data[$key], ['branch','source', 'class','photo']);
        }

        return collect($data);
    }

    /**
     * return Statistics clients by  Source
     * @param int branchId
     */
    public function statisticsSource($branchId)
    {
        return  app()->make(SourceRepositoryInterface::class)->getAll(relationCountWhere: 'clients', condition: ['branch_id' => $branchId], fields: ['id', 'name']);
    }


    /**
     * return Statistics clients by  Company and Individual
     * @param int branchId
     */
    public function statisticsClientType($branchId)
    {
        $countClientCompany = $this->count(parameters: ['branch_id' => $branchId, 'type' => 'company']);
        $countClientIndividual = $this->count(parameters: ['branch_id' => $branchId, 'type' => 'individual']);

        return [
            'company' => $countClientCompany,
            'individual' => $countClientIndividual
        ];
    }

    /**
     * return Statistics clients by  Branch
     * @param int branchId
     */
    public function statisticsClientByBranch($branchId)
    {
        return  app()->make(BranchRepositoryInterface::class)->getAll(relationCountWhere: 'clients', condition: ['branch_id' => $branchId], fields: ['id', 'name']);
    }


    /**
     * return Statistics clients by  Branch
     * @param int branchId
     */
    public function addContact($date,$client_id)
    {

        $instatnce = app()->make(ContactRepositoryInterface::class);
        // TOFIX:
        $id = $instatnce->maxId();
        $data['code'] = 'Contact_' . $id;

        $contact = [
            'code'             => 'Contact_' . $id,
            'branch_id'        => $date['branch_id'],
            'client_id'        => $client_id,
            'first_name'       => $date['first_name'],
            'last_name'        => $date['last_name'],
            'email'            => $date['contact_email'],
            'phone'            => $date['contact_phone'],
            'position'         => $date['contact_position'],

        ];
        return  $instatnce->store($contact);
    }
}
