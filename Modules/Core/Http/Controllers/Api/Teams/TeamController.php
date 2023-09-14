<?php

namespace Modules\Core\Http\Controllers\Api\Teams;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use App\Http\Resources\Users\BriefUserResource;
use Illuminate\Support\Facades\Request;
use Modules\Core\Http\Resources\Teams\TeamResource;
use Modules\Core\Http\Resources\Teams\TeamCollection;
use Modules\Core\Http\Requests\Teams\StoreTeamRequest;
use Modules\Core\Http\Requests\Teams\UpdateTeamRequest;
use Modules\Core\Http\Resources\Teams\TeamBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Core\Repositories\Teams\TeamRepositoryInterface;

class TeamController extends ApiController
{
    /** inject required classes */
    public function __construct(protected TeamRepositoryInterface $teamRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_core_teams')->only('index', 'show');
        $this->middleware('permission:create_core_teams')->only('store');
        $this->middleware('permission:update_core_teams')->only('update');
        $this->middleware('permission:delete_core_teams')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $teams = $this->teamRepository->paginate('id', ['department'], paginate: Request::query('limit') ?? 25,);

        $result = new TeamCollection($teams);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function brief()
    {
        $teams = $this->teamRepository->getAll(fields: ['id', 'name']);

        $result = TeamBriefResource::collection($teams);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreTeamRequest $request)
    {
        $data = $request->validated();

        $team = $this->teamRepository->store($data);

        $result = new TeamResource($team);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("team created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $team = $this->teamRepository->findById($id, ['department']);

        if (!$team) {
            throw new NotFoundHttpException();
        }

        $result =  new TeamResource($team);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateTeamRequest $request, $id)
    {
        $team = $this->teamRepository->findById($id);

        if (!$team) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $team = $this->teamRepository->updateByInstance($team, $data);

        $result = new TeamResource($team);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("team updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $team = $this->teamRepository->findById($id);

        if (!$team) {
            throw new NotFoundHttpException();
        }

        $this->teamRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("team deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->teamRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count teams deleted successfully.")
            ->setCode(200);
    }

    public function usersByTeam($team_id)
    {
        $team = $this->teamRepository->findById($team_id,['users']);

        if (!$team) {
            throw new NotFoundHttpException();
        }

        $users = $team->users;

        $result =  BriefUserResource::collection($users);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }
}
