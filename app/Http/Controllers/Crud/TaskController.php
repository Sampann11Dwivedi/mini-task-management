<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Task as Validate;
use App\Lib\Crud\Task as Lib;
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public static function index(Request $request)
    {
        try {
            $response = ['data' => [],'total' => 0,'last_page' => 1,'current_page' => 1];
            $data = $request->toArray();
            $response = Lib::get($data);

        } catch (\Exception $e) {
             \Log::error($e->getMessage() . " " . $e->getFile() .':'.$e->getLine());
        }
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public static function store(Validate $request)
    {
        try {
            
            $response =  ['error' => 'Unable to create task'];
            $data = $request->toArray();
            $response = Lib::create($data);
        
        } catch (\Exception $e) {
             \Log::error($e->getMessage() . " " . $e->getFile() .':'.$e->getLine());   
        }
        return $response;
        
    }

    /**
     * Display the specified resource.
     */
    public static function show(int $id)
    {
        try {
            $response =  ['error' => 'Oops something went wrong while getting task data'];
            $response = Lib::edit($id);
        } catch (\Exception $e) {
             \Log::error($e->getMessage() . " " . $e->getFile() .':'.$e->getLine());
        }
        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public static function update(Validate $request, string $id)
    {
        try {
            $response =  ['error' => 'Unable to create task'];
            $data = $request->toArray();
            $response = Lib::update($data,$id);
        
        } catch (\Exception $e) {
             \Log::error($e->getMessage() . " " . $e->getFile() .':'.$e->getLine());   
        }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public static function destroy(int $id)
    {
        try {
            $response =  ['error' => 'Error while getting task data'];
            $response = Lib::delete($id);
            
        } catch (\Exception $e) {
             \Log::error($e->getMessage() . " " . $e->getFile() .':'.$e->getLine());
        }
        return $response;
    }
}
