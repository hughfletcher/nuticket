<?php namespace App\Http\Controllers;

use App\Contracts\Repositories\TicketInterface;
use App\Contracts\Repositories\TicketActionInterface;
use App\Contracts\Repositories\UserInterface;
use App\Http\Requests\TicketIndexRequest;
use App\Http\Requests\TicketStoreRequest;
use App\Http\Requests\TicketCreateRequest;
use App\Http\Requests\TicketUpdateRequest;
use App\Events\TicketCreatedEvent;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Carbon\Carbon;
use Auth;

class TicketsController extends BaseController
{
    use DispatchesJobs;

    /**
     * Create a instance.
     * @param TicketInterface       $ticket App\Contracts\Repositories\TicketInterface
     * @param TicketActionInterface $action App\Contracts\Repositories\TicketActionInterface
     * @param UserInterface         $user   App\Contracts\Repositories\UserInterface
     *
     * @return null
    */
    public function __construct(TicketInterface $ticket, UserInterface $user)
    {
        $this->tickets = $ticket;
        $this->user = $user;
    }

    /**
    * Display a listing of the resource.
    * GET /app\s\dash
    *
    * @return Response
    */
    public function index(TicketIndexRequest $request)
    {
        $tickets = $this->tickets->paginateByRequest($request->get('per_page', config('system.page_size')));

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show a ticket.
     * @param  integer $id A ticket id.
     * @return Response
     */
    public function show($userId)
    {
        $ticket = $this->tickets->find($userId);
        return view('tickets.show', compact('ticket'));
    }

    public function create(TicketCreateRequest $request)
    {
        $data = [];
        if ($request->has('user_id') && $user = $this->user->find($request->get('user_id'))) {
            $data['user'] = $user;
        }
        return view('tickets.create', $data);
    }

    public function store(TicketStoreRequest $request)
    {

        if (!$request->has('user_id')) {
            $user = $this->user->create([
                'display_name' => $request->input('display_name'),
                'email' => $request->input('email'),
                'username' =>  preg_replace(
                    "/[^a-zA-Z0-9]/",
                    "",
                    $request->input('display_name')
                ) . rand(10000, 99990)
            ]);
            $request->merge(['user_id' => $user->id]);
        }

        $request->merge(['auth_id' => auth()->user()->id]);

        $ticket = $this->dispatchFrom('App\Jobs\TicketCreateJob', $request);

        $action = collect([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->user()->id,
            'hours' => $request->get('hours'),
            'time_at' => $request->has('time_at') ? Carbon::createFromFormat('m/d/Y', $request->get('time_at'), auth()->user()->tz)->timezone('utc') : null,
            'status' => $request->has('status') ? $request->get('status') : 'open',
            'defer_event' => true
        ]);

        if ($request->has('reply_body')) {
            $this->dispatchFrom(
                'App\Jobs\ActionCreateJob', 
                $action,
                [
                    'type' => in_array($request->get('status'), ['closed', 'resolved']) ? $request->get('status') : 'reply',
                    'body' => $request->get('reply_body')
                ]
            );

            $action->put('hours', 0);
        }

        if ($request->has('comment_body')) {

            $this->dispatchFrom(
                'App\Jobs\ActionCreateJob', 
                $action,
                [
                    'type' => 'comment',
                    'body' => $request->get('comment_body')
                ]
            );
        }

        event(new TicketCreatedEvent($ticket));

        return redirect()->route('tickets.show', [$ticket['id']])
            ->with('message', 'Ticket #' . $ticket['id'] . ' sucessfully created.');

    }

    public function edit($userId)
    {
        $ticket = $this->tickets->find($userId);
        return view('tickets.edit', compact('ticket'));
    }

    public function update(TicketUpdateRequest $request, $ticketId)
    {
        $request->merge(['ticket_id' => $ticketId, 'auth_id' => Auth::user()->id]);

        if (!$action = $this->dispatchFrom('App\Jobs\TicketUpdateJob', $request)) {

            return redirect()->route('tickets.edit', [$ticketId])
            ->with('message', 'There were no changes made to this ticket.')
            ->withInput();
        }

        return redirect()->route('tickets.show', [$ticketId, '#action-' . $action->id]);

    }
}
