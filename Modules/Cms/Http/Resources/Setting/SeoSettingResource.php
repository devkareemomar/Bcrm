<?php

namespace Modules\Cms\Http\Resources\Setting;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class SeoSettingResource extends JsonResource
{
    /**
     * Transform user resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return seo setting on array
        $result =[];
        foreach($this->resource as $data){
            $result[$data->key] =$data->value;

            if($data->key == 'site_map'){
                $result[$data->key] = asset($data->value);
            }
            if($data->key == 'robot'){
                $result[$data->key] = asset($data->value);
            }

        }
        return $result;

    }
}
