<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    protected $registerPayload = ['username' => 'ahmed', 'department_id' => '1', 'email' => 'ahrmerd@gmail.com', 'password' => 'masmam', 'phone' => '09012345678'];
    protected $loginPayloadWithEmail = ['email' => 'ahrmerd@gmail.com', 'password' => 'masmam'];
    protected $loginPayloadWithUsername = ['username' => 'ahmed', 'password' => 'masmam'];
    protected $loginPayloadWithInvalidPassword = ['email' => 'ahrmerd@gmail.com', 'password' => 'maasmam'];

    /** @test */
    public function a_user_can_login_with_email()
    {
        //$this->withoutExceptionHandling();
        $this->post('/api/register', $this->registerPayload)->assertstatus(201);
        Auth::logout();
        $this->assertNotTrue(Auth::check());
        $this->post('/api/login', $this->loginPayloadWithEmail)->assertstatus(200);
        $this->assertTrue(Auth::check());
    }

    /** @test */
    public function a_user_can_login_with_username()
    {
        $this->post('/api/register', $this->registerPayload)->assertstatus(201);
        Auth::logout();
        $this->post('/api/login', $this->loginPayloadWithUsername)->assertstatus(200);
        $this->assertTrue(Auth::check());
    }

    /** @test */
    public function a_user_can_logout()
    {
        $this->post('/api/register', $this->registerPayload)->assertstatus(201);
        $this->assertTrue(Auth::check());
        $this->post('/api/logout')->assertStatus(202)->assertSee('logged out success');
        $this->assertNotTrue(Auth::check());
    }


    /** @test */
    public function error_when_invalid_credentials()
    {
        $this->post('/api/register', $this->registerPayload)->assertstatus(201);
        Auth::logout();
        $this->post('/api/login', $this->loginPayloadWithInvalidPassword)->assertstatus(422);
        $this->assertNotTrue(Auth::check());
    }

    /** @test */
    public function login_requires_username_or_email()
    {
        $this->post('/api/register', ['password' => 'masmam'])->assertstatus(422);
        $this->assertNotTrue(Auth::check());
    }
}
