<?php

namespace Modules\Cms\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * App repositories map
     */
    protected $repositories = [
        'Modules\Cms\Repositories\Categories\CategoryRepositoryInterface' => 'Modules\Cms\Repositories\Categories\CategoryRepository',
        'Modules\Cms\Repositories\Services\ServiceRepositoryInterface' => 'Modules\Cms\Repositories\Services\ServiceRepository',
        'Modules\Cms\Repositories\Galleries\GalleryRepositoryInterface' => 'Modules\Cms\Repositories\Galleries\GalleryRepository',
        'Modules\Cms\Repositories\Sliders\SliderRepositoryInterface' => 'Modules\Cms\Repositories\Sliders\SliderRepository',
        'Modules\Cms\Repositories\Faqs\FaqRepositoryInterface' => 'Modules\Cms\Repositories\Faqs\FaqRepository',
        'Modules\Cms\Repositories\Pages\PageRepositoryInterface' => 'Modules\Cms\Repositories\Pages\PageRepository',
        'Modules\Cms\Repositories\Solutions\SolutionRepositoryInterface' => 'Modules\Cms\Repositories\Solutions\SolutionRepository',
        'Modules\Cms\Repositories\Partners\PartnerRepositoryInterface' => 'Modules\Cms\Repositories\Partners\PartnerRepository',
        'Modules\Cms\Repositories\Jobs\JobRepositoryInterface' => 'Modules\Cms\Repositories\Jobs\JobRepository',
        'Modules\Cms\Repositories\Posts\PostRepositoryInterface' => 'Modules\Cms\Repositories\Posts\PostRepository',
        'Modules\Cms\Repositories\Posts\CommentRepositoryInterface' => 'Modules\Cms\Repositories\Posts\CommentRepository',
        'Modules\Cms\Repositories\Products\ProductRepositoryInterface' => 'Modules\Cms\Repositories\Products\ProductRepository',
        'Modules\Cms\Repositories\Products\ProductPhotoRepositoryInterface' => 'Modules\Cms\Repositories\Products\ProductPhotoRepository',
        'Modules\Cms\Repositories\Messages\MessageRepositoryInterface' => 'Modules\Cms\Repositories\Messages\MessageRepository',
        'Modules\Cms\Repositories\NewsLetters\NewsLetterRepositoryInterface' => 'Modules\Cms\Repositories\NewsLetters\NewsLetterRepository',
        'Modules\Cms\Repositories\Media\MediaRepositoryInterface' => 'Modules\Cms\Repositories\Media\MediaRepository',
        'Modules\Cms\Repositories\Setting\SeoSettingRepositoryInterface' => 'Modules\Cms\Repositories\Setting\SeoSettingRepository'
    ];


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // register app repositories
        foreach ($this->repositories as $interface => $concrete) {
            $this->app->bind($interface, $concrete);
        }
    }
}
