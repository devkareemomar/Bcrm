<?php

namespace Modules\Cms\Repositories\Messages;

use Modules\Cms\Models\Message;
use App\Repositories\BaseEloquentRepository;

class MessageRepository extends BaseEloquentRepository implements MessageRepositoryInterface
{
    /** @var string */
    protected $modelName = Message::class;

    protected $searchableFields = ['name', 'email', 'phone'];
}
