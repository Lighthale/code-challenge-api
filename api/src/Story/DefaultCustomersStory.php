<?php

namespace App\Story;

use App\Factory\CustomerFactory;
use Zenstruck\Foundry\Story;

final class DefaultCustomersStory extends Story
{
    public function build(): void
    {
        CustomerFactory::createMany(100);
    }
}
