<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Repositories\User\UserRepository as UserRepository;
use App\Http\Requests\User\Store;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private object $user_repository;

    public function __construct()
    {
        $this->user_repository = new UserRepository();
    }

    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $this->user_repository->fillStoreRequest($request);
        $this->user_repository->createUserAccount($request);
        return $this->user_repository->response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public  function login(Request $request)
    {
        $this->user_repository->checkLoginConditions($request);
        $this->user_repository->login();
        return $this->user_repository->response;
    }


}
