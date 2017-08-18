<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminTest extends TestCase
{
    /**
     * Test for login
     *
     * @return void
     */
    public function testLogin()
    {
        $this->visit(route('admin.login.get'))
            ->type('qq@ya.ru', 'email')
            ->type('111111', 'password')
            ->press('login')
            ->see('Admin Dashboard');
    }

    /**
     * Negative test for login
     *
     * @return void
     */
    public function testLoginNegative()
    {
        $this->visit(route('admin.login.get'))
            ->type('qq@ya.ru', 'email')
            ->type('222222', 'password')
            ->press('login')
            ->see('Wrong email or password.');
    }

    /**
     * Test for registration
     *
     * @return void
     */
    public function testGetRegistration()
    {
        $this->visit(route('admin.login.get'))
            ->click('Create an account')
            ->see('create');
    }

    /**
     * Test for registration submit and clear new user data after
     *
     * @return void
     */
    public function testPostRegistration()
    {
        $this->visit(route('admin.login.get'))
            ->click('Create an account')
            ->type('test_name', 'name')
            ->type('test_email@ya.ru', 'email')
            ->type('111111', 'password')
            ->press('create')
            ->see('login');

        $this->call('POST', '/admin/users/email', array('email'=> 'test_email@ya.ru'));
        $this->assertResponseOk();
    }

    /**
     * Test for registration submit and clear new user data after
     *
     * @return void
     */
    public function testPostRegistrationNegative()
    {
        $this->visit(route('admin.login.get'))
            ->click('Create an account')
            ->type('test_name', 'name')
            ->type('test_emailya.ru', 'email')
            ->type('111111', 'password')
            ->press('create')
            ->see('login');
    }
}