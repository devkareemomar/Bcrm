<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedCollection extends ResourceCollection
{
    public function __construct($resource)
    {
        parent::__construct($resource);

        if (method_exists($this, '_toArray')) {
            $this->collection = $resource->map(function ($item, $key) {
                return $this->_toArray($item);
            });
        }
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        // extract pagination array
        $pagination = $this->resource->toArray();

        // overrider links label values
        $pagination["links"][0]['label'] = 'Prev';
        $pagination["links"][count($pagination["links"]) - 1]['label'] = 'Next';



        // return custom pagination result to fit with our frontend
        return [
            'data' => $this->collection,
            'pagination' => [
                "current_page" => $pagination['current_page'],
                "from" => $pagination['from'],
                "last_page" => $pagination['last_page'],
                "links" => $pagination['links'],
                "per_page" => $pagination['per_page'],
                "to" => $pagination['to'],
                "total" => $pagination['total'],
            ]
        ];
    }
}
