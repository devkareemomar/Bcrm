<?php

namespace Modules\Crm\Repositories\Contacts;

use App\Exports\BaseExampleExport;
use App\Exports\BaseExport;
use App\Imports\BaseImport;
use Modules\Crm\Models\Contact;
use App\Repositories\BaseEloquentRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ContactRepository extends BaseEloquentRepository implements ContactRepositoryInterface
{
    /** @var string */
    protected $modelName = Contact::class;

    protected $filterableFields = ['client_id','lead_id','city_id','country_id','branch_id'];
    protected $searchableFields = ['first_name','last_name','email'];

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

        $name = 'contacts-exampel-' . random_int(100000, 999999) . '.xlsx';

        return Excel::download(new BaseExampleExport($attributes), $name);
    }

    /**
     * import contacts from file excel
     * @param array request
     * @return array branchId
     */
    public function importData($request, $branchId)
    {
        try {

            // TOFIX:
            $pirfix = 'contact_';
            $code = $this->maxId();

            Excel::import(new BaseImport($this->modelName, $branchId, $pirfix, $code), $request->file);

            return response()->json([
                'success' => '200',
                'message' => 'contacts are successfully imported.'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'error' => '404',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * download contacts into file excel
     * @param array request
     * @return array branchId
     */
    public function downloadExport($request, $branchId)
    {
        $fields = ($request->fields) ? $request->fields :$this->getTableColumns(except: ['id', 'created_at', 'updated_at']);

        $contactsCollection  = $this->getAll(
            relations: ['branch', 'city', 'country', 'photo','client','lead'],
            parameters: ['branch_id' => $branchId],
            fields: $fields ?? ['*']
        );

        $name = 'contacts-' . random_int(100000, 999999) . '.xlsx';

        $contacts = $this->reMappingCollection($contactsCollection);

        return Excel::download(new BaseExport($contacts, $fields), $name);
    }

    /**
     * return contacts collection after custom relationships
     * @param array contactsCollection
     */
    protected function reMappingCollection($contactsCollection)
    {
        $data =  $contactsCollection->toArray();
        foreach ($contactsCollection as $key =>  $contact) {
            $data[$key]['branch_id']  = $contact->branch->name ?? '';
            $data[$key]['city_id']    = $contact->city->name ?? '';
            $data[$key]['country_id'] = $contact->country->name ?? '';
            $data[$key]['client_id']  = isset($contact->client->first_name)? $contact->client->first_name .$contact->client->lasr_name :'';
            $data[$key]['lead_id']    = isset($contact->lead->first_name)? $contact->lead->first_name .$contact->lead->lasr_name :'';
            $data[$key]['photo_id']   = isset($contact->photo) ? asset(Storage::url($contact->photo->file)) : null;
            $data[$key] = Arr::except($data[$key], ['branch', 'city', 'country', 'photo','client','lead']);
        }

        return collect($data);
    }

}
