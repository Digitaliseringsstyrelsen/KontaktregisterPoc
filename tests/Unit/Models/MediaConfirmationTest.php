<?php

namespace Tests\Unit\Models;

use Api\Models\MediaConfirmation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MediaConfirmationTest extends TestCase
{
    /**
     * @test
     */
    public function check()
    {
        $code = '123456';
        $media_confirmation = factory(MediaConfirmation::class)->create([
            'code' => $code
        ]);
        $this->assertTrue($media_confirmation->check($code));
    }

    /**
     * @test
     */
    public function confirm()
    {
        $media_confirmation = factory(MediaConfirmation::class)->create();
        $contact = $media_confirmation->contact;

        $this->assertCount(0, $contact->medias);
        $media_confirmation->confirm();
        $this->assertCount(1, $contact->fresh()->medias);
        $this->assertNull($media_confirmation->fresh());
    }
}
