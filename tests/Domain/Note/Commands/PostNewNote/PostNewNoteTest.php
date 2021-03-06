<?php
declare(strict_types=1);

namespace EMA\Tests\Domain\Note\Commands\PostNewNote;

use Assert\InvalidArgumentException;
use EMA\Domain\Foundation\VO\Identity;
use EMA\Domain\Note\Commands\PostNewNote\PostNewNote;
use EMA\Domain\Note\Model\VO\NoteText;
use EMA\Tests\BaseTest;
use Faker\Factory;

class PostNewNoteTest extends BaseTest
{
    
    function test_public_api()
    {
        
        $faker = Factory::create();
        
        $id       = new Identity();
        $owner_id = new Identity();
        $text     = new NoteText($faker->text());
        
        $command = new PostNewNote($text, $id, $owner_id);
        $this->assertEquals($text, $command->getText());
        $this->assertTrue($id->isEqual($command->getId()));
        $this->assertTrue($owner_id->isEqual($command->getOwnerId()));
        
    }
    
}
