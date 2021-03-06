<?php

namespace Pterodactyl\Http\Controllers\Admin\Servers;

use Illuminate\Http\Request;
use Pterodactyl\Models\Server;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Contracts\View\Factory;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Repositories\Eloquent\ServerRepository;

class ServerController extends Controller
{
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $view;

    /**
     * @var \Pterodactyl\Repositories\Eloquent\ServerRepository
     */
    private $repository;

    /**
     * ServerController constructor.
     *
     * @param \Illuminate\Contracts\View\Factory $view
     * @param \Pterodactyl\Repositories\Eloquent\ServerRepository $repository
     */
    public function __construct(
        Factory $view,
        ServerRepository $repository
    ) {
        $this->view = $view;
        $this->repository = $repository;
    }

    /**
     * Returns all of the servers that exist on the system using a paginated result set. If
     * a query is passed along in the request it is also passed to the repository function.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $servers = QueryBuilder::for(Server::query()->with('node', 'user', 'allocation'))
            ->allowedFilters(['uuid', 'name', 'image'])
            ->allowedSorts(['id', 'uuid'])
            ->paginate(config()->get('pterodactyl.paginate.admin.servers'));

        return $this->view->make('admin.servers.index', ['servers' => $servers]);
    }
}
