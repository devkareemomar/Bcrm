<?php

namespace Modules\Cms\Http\Controllers\Api\Setting;

use App\Http\Controllers\Api\ApiController;
use App\Services\FileService;
use Modules\Cms\Http\Requests\Setting\UpdateSeoSettingRequest;
use Modules\Cms\Http\Resources\Setting\SeoSettingResource;
use Modules\Cms\Repositories\Setting\SeoSettingRepositoryInterface;

class SeoSettingController extends ApiController
{
    public function __construct(protected SeoSettingRepositoryInterface $seoRepository,protected FileService $fileService)
    {
        /** Settingmiddlewares */
        $this->middleware('permission:read_cms_seo_setting')->only('index');
        $this->middleware('permission:update_cms_seo_setting')->only('update');
    }

    public function index()
    {

        $seo = $this->seoRepository->getAllSeoSetting();
        $result =  new SeoSettingResource($seo);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }



    public function update(UpdateSeoSettingRequest $request)
    {
        // save site-map file and robot file
        $this->saveSeoFiles($request->validated());

        $requestData = $request->validated();
        unset($requestData['site_map'],$requestData['robot']);
        // dd($requestData);
        // save seo setting
        foreach($requestData as $key =>  $value ){

            $seo = $this->seoRepository->findBy('key' ,$key);
            $data = ['key' => $key ,'value' => $value];
            $this->seoRepository->updateByInstance($seo, $data);

        }
        // return seo setting
        $seo = $this->seoRepository->getAllSeoSetting();
        $result =  new SeoSettingResource($seo);

        return $this->jsonResponse()->setStatus(true)
                    ->setCode(201)
                    ->setResult($result);
    }

    protected function saveSeoFiles($requestData)
    {
        // save site_map file
        if (isset($requestData['site_map'])) {
            $this->fileService->deleteSeoFile('site-map.xml'); // delete old site_map
            $requestData['site_map'] = $this->fileService->saveSeoFile($requestData['site_map'],'site-map');
        }
        // save robot file
        if (isset($requestData['robot'])) {
            $this->fileService->deleteSeoFile('robot.txt'); // delete old robot
            $requestData['robot'] = $this->fileService->saveSeoFile($requestData['robot'],'robot');

        }
    }



}
