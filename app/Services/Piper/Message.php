<?php namespace App\Services\Piper;

use EmailReplyParser\Parser\EmailParser;
use App\Services\Piper\Pipes\UserPipe;
use App\Contracts\Repositories\OrgInterface;

abstract class Message
{
    protected $eachTagsRegex = '/^#([a-z]+)[[:blank:]]*([[:alnum:]\.,@\?[[:blank:]]*)/m';

    protected $noTagsRegex = '/^[^#\n]{1}(.*)/ms';

    protected $ticketIdRegex = '/\[.*#([[:digit:]]*)\]\s{1}.*/';

    protected $data;

    protected $message;

    public function __construct(EmailParser $parser, UserPipe $user, OrgInterface $org)
    {
        $this->parser = $parser;
        $this->user = $user;
        $this->org = $org;
        $this->data = collect();
    }

    abstract protected function getMessageBody();

    abstract protected function getFromName();

    abstract protected function getFromEmail();

    public function set($message)
    {
        $this->message = $message;
    }

    protected function getBulk()
    {
        $body = $this->parser->parse($this->getMessageBody());
        return current($body->getFragments());
    }

    public function getSkinny()
    {
        return $this->clearTags($this->getBulk());
    }

    protected function clearTags($string)
    {
        preg_match($this->noTagsRegex, $string, $matches);

        if (isset($matches[0])) {
            return trim($matches[0]);
        }

        return null;
    }

    public function getTicketId()
    {
        $found = preg_match($this->ticketIdRegex, $this->getSubject(), $id);

        if ($found === 0) {
            return null;
        }

        return $id[1];

    }

    public function getTags()
    {
        if ($this->getAuthor()->cannot('use-tags')) {
            return collect();
        }

        if ($this->data->has('tags')) {
            return clone $this->data->get('tags');
        }

        return clone $this->data->put('tags', $this->parseTags())->get('tags');
    }

    protected function parseTags()
    {
        $matches = $this->applyTagRegex($this->getBulk());
        $tags = collect(array_combine($matches[1], $matches[2]));

        return $tags->map(function ($item) {
            return trim($item);
        });
    }

    protected function applyTagRegex($string)
    {
        preg_match_all($this->eachTagsRegex, $string, $matches);
        return $matches;
    }

    public function getAuthor()
    {
        if ($this->data->has('author')) {
            return $this->data->get('author');
        }

        return $this->data->put('author', $this->user->findOrCreate($this->getFromName(), $this->getFromEmail()))
            ->get('author');
    }

    public function getAuthorId()
    {
        return $this->getAuthor()->id;
    }

    public function getUserId()
    {
        return $this->getUser()->id;
    }

    public function getUser()
    {
        if ($this->data->has('user')) {
            return $this->data->get('user');
        }

        $tags = $this->getTags();
        if ($tags->has('user')) {
            return $this->data->put('user', $this->user->findByUserName($tags->get('user')))->get('user');
        }

        return $this->getAuthor();
    }

    public function getAssignedId()
    {
        if ($this->data->has('assigned')) {
            return $this->data->get('assigned')->id;
        }

        $tags = $this->getTags();
        if ($tags->has('assign')) {
            return $this->data->put('assigned', $this->user->findByUserName($tags->get('assign')))->get('assigned')->id;
        }

        if ($tags->has('claim')) {
            return $this->data->put('assigned', $this->getAuthor())->get('assigned')->id;
        }

        return null;
    }

    public function getOrgId()
    {
        if ($this->data->has('org')) {
            return $this->data->get('org')->id;
        }

        $tags = $this->getTags();
        if ($tags->has('org')) {
            $org = $this->org->findWhere([
            ['name', 'like', '%' . $tags->get('org') . '%']
            ], ['id'])->first();
            if ($org) {
                $this->data->put('org', $org);
                return $org->id;
            }
        }

        return null;
    }

    public function setCreatedTicket(Ticket $ticket)
    {
        $this->data->put('ticket', $ticket);
        return true;
    }

    public function getCreatedTicket()
    {
        return $this->data->get('ticket');
    }

    public function getCreatedTicketId()
    {
        if (!$this->data->has('ticket')) {
            return;
        }
        
        return $this->data->get('ticket')->id;
    }

}
