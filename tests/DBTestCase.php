<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class DBTestCase extends TestCase
{
    use RefreshDatabase;
    
}
