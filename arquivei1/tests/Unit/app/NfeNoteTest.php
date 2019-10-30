<?php

namespace Tests\Unit;

use App\NfeNote;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class NfeNoteTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSaveNfe()
    {
        $nfeNote = factory(NfeNote::class)->make();
        $nfeNote->save();

        $savedNfeNotes = NfeNote::find($nfeNote->id);

        $this->assertEquals($nfeNote->id, $savedNfeNotes->id);
    }
}
