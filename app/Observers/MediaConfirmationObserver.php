<?php

namespace Digist\Observers;

use Api\Models\MediaConfirmation;

class MediaConfirmationObserver
{
    public function confirmed(MediaConfirmation $mediaConfirmation)
    {
        // log here
        $mediaConfirmation->delete();
    }
}
