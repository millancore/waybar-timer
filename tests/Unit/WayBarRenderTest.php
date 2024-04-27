<?php

namespace Timer\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Timer\WaybarRender;

#[CoversClass(WaybarRender::class)]
class WayBarRenderTest extends TestCase
{

    public function test_create_render_as_json()
    {
        $render = new WaybarRender('text', 'alt', 'tooltip', 'class');

        $this->assertEquals(
            json_encode([
                'text' => 'text',
                'alt' => 'alt',
                'tooltip' => 'tooltip',
                'class' => 'class',
            ]),
            $render->toJson()
        );
    }

    public function test_update_property() : void
    {
        $render = new WaybarRender('text', 'alt', 'tooltip', 'class');

        $render->text = 'new text';

        $this->assertEquals('new text', $render->text);
    }

}