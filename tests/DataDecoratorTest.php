<?php namespace Push\Decorators\Tests;

use DateTime;
use Push\Decorators\DataDecorator;

class DataDecoratorTest extends AbstractTestCase
{
    public function setUp()
    {
        $this->input = [
            'number' => 40,
            'string' => 'this is a string',
            'datetime' => new DateTime,
        ];

        parent::mockAbstract('Push\Decorators\DataDecorator');

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
        $this->assertEquals(40, $this->decorator->get('number'));
        $this->assertEquals('this is a string', $this->decorator->get('string'));
        $this->assertEquals(null, $this->decorator->get('null'));
        $this->assertEquals('default', $this->decorator->get('default', 'default'));
    }

    public function testOnly()
    {
        $one = [
            'number' => 40,
        ];
        $this->assertEquals($one, $this->decorator->only('number'));

        $multi = [
            'number' => 40,
            'string' => 'this is a string',
        ];
        $this->assertEquals($multi, $this->decorator->only('number', 'string'));

        $array = [
            'number' => 40,
            'string' => 'this is a string',
        ];
        $this->assertEquals($array, $this->decorator->only(['number', 'string']));

        $missing = [
            'number' => 40,
            'bad' => null,
        ];
        $this->assertEquals($missing, $this->decorator->only('bad', 'number'));

        $empty = [
            'bad' => null,
        ];
        $this->assertEquals($empty, $this->decorator->only('bad'));
    }

    public function testExcept()
    {
        $one = [
            'string' => 'this is a string',
            'datetime' => new DateTime,
        ];
        $this->assertEquals($one, $this->decorator->except('number'));

        $multi = [
            'datetime' => new DateTime,
        ];
        $this->assertEquals($multi, $this->decorator->except('number', 'string'));

        $array = [
            'string' => 'this is a string',
        ];
        $this->assertEquals($array, $this->decorator->except('number', 'datetime'));

        $missing = [
            'number' => 40,
            'string' => 'this is a string',
        ];
        $this->assertEquals($missing, $this->decorator->except('missing', 'datetime'));
    }

    public function testMerge()
    {
        $add = [
            'array' => [
                'a', 'b',
            ],
            'string' => 'string has changed',
            'no-key',
        ];
        $this->decorator->merge($add);

        $sum = [
            'number' => 40,
            'datetime' => new DateTime,
            'array' => [
                'a', 'b',
            ],
            'string' => 'string has changed',
            'no-key',
        ];
        $this->assertEquals($sum, $this->decorator->all());

        $nested = [
            'a', 'b',
        ];
        $this->assertEquals($nested, $this->decorator->get('array'));
    }
}
