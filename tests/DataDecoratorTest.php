<?php namespace Decorators\Tests;

use DateTime;
use Decorators\DataDecorator;

class DataDecoratorTest extends AbstractTestCase
{
    public function setUp()
    {
        $this->input = [
            'number' => 40,
            'string' => 'this is a string',
            'datetime' => new DateTime,
        ];

        parent::mockAbstract('Decorators\DataDecorator');

        $this->setProperty('data', $this->input);
        $this->decorator = $this->mocked;
    }

    public function testAll()
    {
        $this->assertSame($this->input, $this->decorator->all());
    }

    public function testHas()
    {
        $this->assertTrue($this->decorator->has('number'));
        $this->assertTrue($this->decorator->has('string'));
        $this->assertTrue($this->decorator->has('datetime'));
        $this->assertFalse($this->decorator->has('bad'));
    }

    public function testGet()
    {
        $this->assertEquals($this->decorator->get('number'), 40);
        $this->assertEquals($this->decorator->get('default', 'default'), 'default');
    }
}
