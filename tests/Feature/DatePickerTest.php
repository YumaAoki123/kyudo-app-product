<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DatePickerTest extends TestCase
{
    public function testDatePicker()
    {
        $response = $this->postJson(route('post.saveSelectedDate'), [
            'selectedDate' => '2023-06-02',

        ]);

        $response->assertStatus(200); // レスポンスステータスが正常 (200) であることを確認
        $response->assertJson(['success' => true]); // 返されたJSONに指定のキーと値が含まれているかを確認

        $this->assertEquals('2023-06-02', session('selected_date')); // セッションに正しい日付が保存されているかを確認
    }
}
