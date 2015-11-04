<?php namespace App\Http\Controllers;

use App\Contracts\Repositories\TicketInterface;
use App\Repositories\TicketActionInterface;
use App\Contracts\Repositories\UserInterface;
use App\Http\Requests\TicketIndexRequest;
use App\Http\Requests\TicketStoreRequest;
use App\Http\Requests\TicketCreateRequest;
use App\Http\Requests\TicketUpdateRequest;
use Auth;

class TicketsController extends BaseController
{
    /**
     * Create a instance.
     * @param TicketInterface       $ticket App\Contracts\Repositories\TicketInterface
     * @param TicketActionInterface $action App\Contracts\Repositories\TicketActionInterface
     * @param UserInterface         $user   App\Contracts\Repositories\UserInterface
     *
     * @return null
    */
    public function __construct(TicketInterface $ticket, TicketActionInterface $action, UserInterface $user)
    {
        $this->tickets = $ticket;
        $this->action = $action;
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

        $ticket = $this->tickets->create(array_add($request->except(['hours', 'display_name', 'email']), 'auth_id', Auth::user()->id));

        $hours = $request->get('hours');

        if ($request->has('reply_body')) {
            $this->action->create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::user()->id,
                'type' => in_array($request->get('status'), ['closed', 'resolved']) ? $request->get('status') : 'reply',
                'body' => $request->get('reply_body'),
                'hours' => $hours,
                'time_at' => $request->get('time_at'),
                'status' => $request->has('status') ? $request->get('status') : 'open'
            ]);
            $hours = 0;
        }

        if ($request->has('comment_body')) {
            $this->action->create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::user()->id,
                'type' => 'comment',
                'body' => $request->get('comment_body'),
                'hours' => $hours,
                'time_at' => $request->get('time_at'),
                'status' => $request->has('status') ? $request->get('status') : 'open'
            ]);
        }

        event(new TicketCreatedEvent($ticket, true));

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
        $attrs = $request->all();

        //get old ticket/sction data so we can see if anything is changing
        $oldTicket = $this->tickets->find($ticketId);
        $oldCreate = $this->action->findWhere(['ticket_id' => $ticketId, 'type' => 'create'])[0]->toArray();

        //lets see what's exactly changing
        $changed = [];

        if ($attrs['user_id'] != $oldTicket['user_id']) {
            $changed[] = 'user_id';
        }

        if ($attrs['priority'] != $oldTicket['priority']) {
            $changed[] = 'priority';
        }

        if ($attrs['title'] != $oldCreate['title']) {
            $changed[] = 'title';
        }

        if ($attrs['body'] != $oldCreate['body']) {
            $changed[] = 'body';
        }

        $oldTicket = array_merge($oldTicket->toArray(), array_only($oldCreate, ['title', 'body']));

        //no changes, user is wasting our time
        if (empty($changed)) {
            return redirect()->route('tickets.edit', [$ticketId])
            ->with('message', 'There were no changes made to this ticket.')
            ->withInput();
        }

        //update ticket/create action
        $this->tickets->update(array_only($attrs, ['user_id', 'priority']), $ticketId);
        $this->action->update(array_only($attrs, ['body', 'title']), $oldCreate['id']);

        //lets create a edit action
        $body = '';
        foreach ($changed as $change) {
            $body .= $change . ' changed from ' . $oldTicket[$change] . ' to ' . $attrs[$change] . "\n";
        }

        $newAction = $this->action->create([
            'ticket_id' => $ticketId,
            'user_id' => Auth::user()->id,
            'type' => 'edit',
            'body' => $body . "\n" . $attrs['reason']
        ]);

        return redirect()->route('tickets.show', [$ticketId, '#action-' . $newAction['id']]);
    }
}
