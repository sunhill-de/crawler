<?php
namespace Sunhill\Crawler\Tests\Unit;

use Sunhill\Basic\Tests\SunhillTestCase;
use Sunhill\Basic\Utils\DescriptorException;
use Sunhill\Crawler\CrawlerDescriptor;
use Tests\CreatesApplication;

class DescriptorTest extends SunhillTestCase
{
  
      use CreatesApplication;
      
      /**
       * @dataProvider HelperProvider
       */
      public function testHelpers($fields,$method,$expect)
      {
        if ($expect === 'except') {  
          $this->expectException(DescriptorException::class);  
        }
        $test = new CrawlerDescriptor();
        if (is_array($fields)) {
            foreach ($fields as $key => $value) {
              $test->$key = $value;
            }
        }
        $this->assertEquals($expect,$test->$method());
      }
  
      public function HelperProvider()
      {
        return [
          [null,'alreadyInDatabase','except'],
          [['fileInDatabase'=>true],'alreadyInDatabase',true],
          [['fileReadable'=>true],'fileProcessable',true], 
        ];
      }
}
