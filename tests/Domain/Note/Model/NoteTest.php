<?php
declare(strict_types=1);

namespace EMA\Tests\Domain\Note\Model\VO;

use Assert\InvalidArgumentException;
use Carbon\Carbon;
use EMA\Domain\Foundation\VO\Identity;
use EMA\Domain\Note\Events\NoteDeleted;
use EMA\Domain\Note\Events\NoteModified;
use EMA\Domain\Note\Events\NotePosted;
use EMA\Domain\Note\Model\Note;
use EMA\Domain\Note\Model\VO\NoteText;
use EMA\Tests\BaseTest;
use Faker\Factory;

final class NoteTest extends BaseTest
{
    function test_public_api()
    {
        Carbon::setTestNow(Carbon::parse("01.01.2011 00:00:00"));
        
        $faker    = Factory::create();
        $id       = new Identity();
        $owner_id = new Identity();
        $text     = new NoteText($faker->text());
        
        $note = new Note($id, $text, $owner_id);
        $this->assertTrue($id->isEqual($note->getId()));
        $this->assertTrue($owner_id->isEqual($note->getOwnerId()));
        $this->assertEquals($text, $note->getText());
        $this->assertEquals(Carbon::now(), $note->getPostedAt());
        $this->assertEquals(Carbon::now(), $note->getModifiedAt());
    }
    
    function test_it_fires_domain_events()
    {
        Carbon::setTestNow(Carbon::parse("01.01.2011 00:00:00"));
        
        $faker    = Factory::create();
        $id       = new Identity();
        $owner_id = new Identity();
        $text     = new NoteText($faker->text());
        
        // 1. Make new note
        $note = Note::make($id, $text, $owner_id);
        $this->assertEquals(NotePosted::class, get_class($note->pullDomainEvents()[0]));
        
        // 2. Modify  note
        $note->modify($text);
        $this->assertEquals(NoteModified::class, get_class($note->pullDomainEvents()[0]));
    
        // 3. Delete  note
        $note->delete();
        $this->assertEquals(NoteDeleted::class, get_class($note->pullDomainEvents()[0]));
        
    }
    
}