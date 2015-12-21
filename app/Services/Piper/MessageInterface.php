<?php namespace App\Services\Piper;

use EmailReplyParser\Parser\EmailParser;
use App\Services\Piper\Pipes\UserPipe;

interface MessageInterface
{
    public function __construct(EmailParser $parser, UserPipe $user);
    public function set($message);
    public function getSkinny();
    public function getSubject();
    public function getTicketId();
    public function getTags();
    public function getAuthor();
    public function getAuthorId();
    public function getUserId();
    public function getAssignedId();
    public function delete();
    public function move($destination);
}
