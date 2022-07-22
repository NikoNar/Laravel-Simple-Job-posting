<?php

namespace App\Helpers;

class GlobalMethods
{
    public static function forceDelete($model,$id) : bool
    {
        $data = $model::onlyTrashed()->find($id);
        if(!empty($data) && $data->forceDelete())
            return true;
        else
            return false;

    }

    public static function restore($model,$id): bool
    {
        $data = $model::onlyTrashed()->find($id);
        if(!empty($data) && $data->restore())
            return true;
        else
            return false;
    }

    public static function delete($model): bool
    {
        return (bool)$model->delete();
    }

    public static function update(&$model,$request): bool
    {
        return (bool)$model->fill($request->all())->save();
    }
}
