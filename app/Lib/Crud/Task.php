<?php

namespace App\Lib\Crud;
use App\Models\Task as Model;
use DB;

class Task
{
    public static function get(array $data)
    {
        try {            
            $response = ['data' => [],'total' => 0,'last_page' => 1,'current_page' => 1];
            
            $query = DB::table('tasks');

            if(isset($data['search'])) $query = Model::where('title','like','%'.$data['search'].'%');

            $query = self::filterQuery($data,$query);

            $limit = $data['limit']??10;
            $page = $data['page'] ?? 1;
            $taskList = $query->paginate($limit,['*'],'page',$page)->toArray();
            $response = \Arr::only($taskList,['data','last_page','total','current_page']);           

        } catch (\Exception $e) {
             \Log::error($e->getMessage() . " " . $e->getFile() .':'.$e->getLine());
        }
        return $response;
    }

    public static function filterQuery(array $data,$query)
    {
        try{
            if(isset($data['priority']))
            {                
                $query = $query->where('priority',$data['priority']);
            }

            if(isset($data['status']))
            {
                $query = $query->where('status',$data['status']);
            }
        }catch(\Exception $e){
            CustomLog::error("unable to format query for filter on location list for Shop:".self::$shopData->shop_name);
            CustomLog::logError($e);
        }
        return $query;
    }

    public static function create(array $data): array
    {
        try {
            $response =  ['error' => 'Error while creating task data'];
            $task = Model::create($data);
            $response = ['status'=> true ,'message' => 'task created successfully'];
        } catch (\Exception $e) {
             \Log::error($e->getMessage() . " " . $e->getFile() .':'.$e->getLine());
        }
        return $response;
    }

    public static function edit(int $id): array
    {
        try {
            $response =  ['error' => 'Error while getting task data'];
            $task = Model::find($id);

            if (empty($task)) return ['error' => 'Task not found'];
            
            $response = $task; 
        } catch (\Exception $e) {
             \Log::error($e->getMessage() . " " . $e->getFile() .':'.$e->getLine());
        }
        return $response;
    }

    public static function update(array $data, int $id): array
    {
        try {
            $response =  ['error' => 'Error while updating task data'];
            $task = Model::find($id);

            if (empty($task)) return ['error' => 'Task not found'];

            $task->update($data);
            $response = ['status'=> true ,'message' => 'task updated successfully'];
        } catch (\Exception $e) {
             \Log::error($e->getMessage() . " " . $e->getFile() .':'.$e->getLine());
        }
        return $response;
    }

    public static function delete(int $id): array
    {
        try {
            $response =  ['error' => 'Error while getting task data'];
            $task = Model::find($id);

            if (empty($task)) return ['error' => 'Task not found'];

            $task->delete();
            
            $response = ['status'=> true ,'message' => 'task deleted successfully']; 
        } catch (\Exception $e) {
             \Log::error($e->getMessage() . " " . $e->getFile() .':'.$e->getLine());
        }
        return $response;
    }
}