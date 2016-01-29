<?php namespace App\Services\Piper;

use EmailReplyParser\Parser\EmailParser;
use App\Services\Piper\Pipes\UserPipe;
use App\Contracts\Repositories\OrgInterface;


interface MessageInterface
{
    public function __construct(EmailParser $parser, UserPipe $user, OrgInterface $org);
    public function set($message);
    public function getSkinny();
    public function getSubject();
    public function getTicketId();
    public function getTags();
    public function getAuthor();
    public function getAuthorId();
    public function getUser();
    public function getUserId();
    public function getAssignedId();
    public function getOrgId();
    public function delete();
    public function move($destination);
}
