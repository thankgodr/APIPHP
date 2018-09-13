<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth'], function(Router $api) {
        $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
        $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');
        $api->post('recovery', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');
        $api->post('logout', 'App\\Api\\V1\\Controllers\\LogoutController@logout');
    
    });

    $api->group(['middleware' => 'jwt.auth'], function(Router $api) {
        

        $api->get('getteams', 'App\\Api\\V1\\Controllers\\TeamsControllers@getTeams');
            //User actions
        $api->post('details', 'App\\Api\\V1\\Controllers\\SignUpController@getUserDetails');
        $api->post('delete', 'App\\Api\\V1\\Controllers\\SignUpController@delete');
        $api->post('update', 'App\\Api\\V1\\Controllers\\SignUpController@updateUser');
        $api->get('myteams', 'App\\Api\\V1\\Controllers\\TeamsControllers@getMyTeam');
        $api->post('jointeam', 'App\\Api\\V1\\Controllers\\TeamsControllers@addMyseltToTeam');
        $api->post('leaveteam', 'App\\Api\\V1\\Controllers\\TeamsControllers@removeMeFromteam');

            //admin actions
        $api->group(['middleware' => 'admin', 'prefix' => 'admin'], function(Router $api){
            $api->post('adduser', 'App\\Api\\V1\\Controllers\\SignUpController@adminAddUser');
            $api->post('updateuser', 'App\\Api\\V1\\Controllers\\SignUpController@adminUpdateUser');
            $api->post('deleteuser', 'App\\Api\\V1\\Controllers\\SignUpController@adminDeleteUser');
            $api->post('getuser', 'App\\Api\\V1\\Controllers\\SignUpController@adminGetUser');
            $api->post('getuserteams', 'App\\Api\\V1\\Controllers\\TeamsControllers@getUserTeam');
            $api->post('addusertoteam', 'App\\Api\\V1\\Controllers\\TeamsControllers@addUserTOTeam');
            $api->post('deleteteam', 'App\\Api\\V1\\Controllers\\TeamsControllers@deleteTeam');
            $api->post('updateteam', 'App\\Api\\V1\\Controllers\\TeamsControllers@updateTeam');
            $api->post('createteam', 'App\\Api\\V1\\Controllers\\TeamsControllers@createTeams');
        });

    });

});
