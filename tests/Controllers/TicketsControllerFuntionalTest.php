<?php namespace Tests\Controllers;

use Tests\TestCase;
use Mockery as m;
use App\User;
use App\Ticket;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Artisan;

class TicketControllerFunctionalTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->runDatabaseMigrations();
        $this->seed();
        config(['modules.path' => base_path('nomodules')]);
    }

    public function testAllRequiresLogin()
    {
        $this->visit('/tickets')->seePageIs('/session/create');
        $this->visit('/tickets/create')->seePageIs('/session/create');
        $this->visit('/tickets/290/edit')->seePageIs('/session/create');
        $this->visit('/tickets/146')->seePageIs('/session/create');
    }

    public function testIndex()
    {
        $this->actingAs(factory(User::class, 'staff')->make())
            ->visit('/tickets')
            ->seePageIs('/tickets')
            ->assertViewHas(['open_count', 'close_count', 'assigned_count', 'tickets']);
    }

    public function testIndexWithCreatedAtSortAsc()
    {
        $this->actingAs(factory(User::class, 'staff')->make())
            ->visit('/tickets?sort=created_at&order=asc')
            ->seePageIs('/tickets?order=asc&sort=created_at')
            ->see('10/14/2005');
    }

    public function testIndexWithCreatedAtSortDesc()
    {
        $this->actingAs(factory(User::class, 'staff')->make())
            ->visit('/tickets?sort=created_at&order=desc')
            ->seePageIs('/tickets?order=desc&sort=created_at')
            ->see('09/24/2015');
    }

    public function testShow()
    {
        $this->actingAs(factory(User::class, 'staff')->make())
            ->visit('/tickets/261')
            ->seePageIs('/tickets/261')
            ->assertViewHas(['staff', 'depts', 'ticket']);
    }

    public function testCreateNoUserRequested()
    {
        $this->actingAs(factory(User::class, 'staff')->make())
            ->visit('/tickets/create')
            ->seePageIs('/tickets/create')
            ->assertViewMissing('user');
    }

    public function testCreateUserRequested()
    {
        $this->actingAs(factory(User::class, 'staff')->make())
            ->visit('/tickets/create?user_id=56')
            ->seePageIs('/tickets/create?user_id=56')
            ->assertViewHas('user');
    }

    public function testCreateUserRequestedButDoesNotExist()
    {
        $this->actingAs(factory(User::class, 'staff')->make())
            ->visit('/tickets/create?user_id=241')
            ->seePageIs('/tickets/create')
            ->assertViewMissing('user');
    }

    public function testStoreCreateUser()
    {
        $this->startSession();

        $data = factory(Ticket::class, 'ticket_create')->make()->toArray();
        array_forget($data, ['user_id', 'status', 'hours', 'time_at', 'reply_body', 'comment_body']);
        $data['_token'] = csrf_token();
        // [dept_id, assigned_id, priority, display_name, email, title, body, _token]

        $this->actingAs(factory(User::class, 'staff')->make())
            ->expectsEvents(['App\Events\UserCreatedEvent'])
            ->post('/tickets', $data)
            ->followRedirects()
            ->seePageIs('/tickets/301')
            ->seeInDatabase(
                'tickets',
                array_merge(
                    array_except($data, ['title', 'body', 'display_name', 'email', '_token']),
                    ['id' => 301, 'user_id' => 201, 'status' => 'new', 'last_action_at' => null, 'hours' => 0]
                )
            )// [dept_id, assigned_id, priority, id, user_id, status, last_action_at, hours]
            ->seeInDatabase(
                'ticket_actions',
                [
                    'id' => 2093,
                    'ticket_id' => 301,
                    'user_id' => $this->app['auth']->user()->id,
                    'type' => 'create',
                    'title' => $data['title'],
                    'body' => $data['body']
                ]
            )
            ->seeInDatabase('users', ['id' => 201, 'display_name' => $data['display_name'], 'email' => $data['email']]);

    }

    public function testStoreTicketOnly()
    {
        $this->startSession();

        $data = factory(Ticket::class, 'ticket_create')->make()->toArray();
        array_forget($data, ['display_name', 'email', 'status', 'hours', 'time_at', 'reply_body', 'comment_body']);
        $data['_token'] = csrf_token();
        // [user_id, dept_id, assigned_id, priority, title, body, _token]

        $this->actingAs(factory(User::class, 'staff')->make())
            ->post('/tickets', $data)
            ->followRedirects()
            ->seePageIs('/tickets/301')
            ->seeInDatabase(
                'tickets',
                array_merge(
                    array_except($data, ['title', 'body', '_token']),
                    ['id' => 301, 'status' => 'new', 'last_action_at' => null, 'hours' => 0]
                )
            )// [user_id, dept_id, assigned_id, priority, id, status, last_action_at, hours]
            ->seeInDatabase(
                'ticket_actions',
                [
                    'id' => 2093,
                    'ticket_id' => 301,
                    'user_id' => $this->app['auth']->user()->id,
                    'type' => 'create',
                    'title' => $data['title'],
                    'body' => $data['body']
                ]
            );
    }

    public function testStoreWithOnlyReply()
    {
        $this->startSession();

        $data = factory(Ticket::class, 'ticket_create')->make()->toArray();
        array_forget($data, ['display_name', 'email', 'status', 'hours', 'time_at', 'comment_body']);
        $data['_token'] = csrf_token();
        // [user_id, dept_id, assigned_id, priority, title, body, reply_body, _token]

        $this->actingAs(factory(User::class, 'staff')->make())
            ->post('/tickets', $data)
            ->followRedirects()
            ->seePageIs('/tickets/301')
            ->seeInDatabase(
                'tickets',
                array_merge(
                    array_except($data, ['title', 'body', '_token', 'reply_body']),
                    ['id' => 301, 'status' => 'open', 'hours' => 0]
                )
            )// [user_id, dept_id, assigned_id, priority, id, status, last_action_at, hours]
            ->seeInDatabase(
                'ticket_actions',
                [
                    'id' => 2093,
                    'ticket_id' => 301,
                    'user_id' => $this->app['auth']->user()->id,
                    'type' => 'create',
                    'title' => $data['title'],
                    'body' => $data['body']
                ]
            )
            ->seeInDatabase(
                'ticket_actions',
                [
                    // 'id' => 2094,
                    'ticket_id' => 301,
                    'user_id' => $this->app['auth']->user()->id,
                    'type' => 'reply',
                    // 'title' => null,
                    'body' => $data['reply_body']
                ]
            );
            // dd(\App\Ticket::find(301)->toArray());
            // dd(\App\TicketAction::find(2094)->toArray());
    }

    public function testStoreWithReplyAndComment()
    {
        # code...
    }

    public function testStoreWithOnlyReplyStatusClosed()
    {
        # code...
    }

    public function testStoreWithReplyStatusResolved()
    {
        # code...
    }

    public function testStoreWithReplyStatusOpen()
    {
        # code...
    }

    public function testStoreWithReplyAndCommentWithHours()
    {
        # code...
    }

    public function testStoreWithCommentWithHours()
    {
        # code...
    }
}
