<?php

namespace App\Http\Controllers\Feature;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\FeatureGroup;

class GroupFeatureController extends Controller
{
    // ========================================>
    // ## Display a listing of the resource.
    // ========================================>
    public function index(Request $request)
    {   
        // ? Initial params
        $params = $this->getParams($request);

        // ? Begin
        $query = FeatureGroup::query()->with(['features'])
            ->search($request->get("search", ''))
            ->filter(json_decode($params["filter"]))
            ->orderBy($params["sortBy"], $params["sortDirection"])
            ->selectableColumns()
            ->paginate($params["paginate"]);

        // ? Response
        $this->responseData($query->all(), $query->total());
    }

    // =============================================>
    // ## Store a newly created resource.
    // =============================================>
    public function store(Request $request)
    {
        // ? Validate request
        $this->validation($request->all(), [
            "name" => "required|string|max:20|unique:feature_groups,name",
        ]);

        // ? Initial
        DB::beginTransaction();
        $model = new FeatureGroup();

        // ? Dump data
        $model->dumpField($request);

        // ? Executing
        try {
            $model->save();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->responseError($th, 'Create FeatureGroup');
        }

        // ? final
        DB::commit();
        $this->responseSaved($model->toArray());
    }

    // ============================================>
    // ## Update the specified resource.
    // ============================================>
    public function update(Request $request, string $id)
    {
        // ? Initial
        DB::beginTransaction();
        $model = FeatureGroup::findOrFail($id);

        // ? Validate request
        $this->validation($request->all(), [
            "name" => "required|string|max:20|unique:feature_groups,name",
        ]);

        // ? Dump data
        $model->dumpField($request);

        // ? Executing
        try {
            $model->save();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->responseError($th, 'Update FeatureGroup');
        }

        // ? final
        DB::commit();
        $this->responseSaved($model->toArray());
    }

    // ===============================================>
    // ## Remove the specified resource.
    // ===============================================>
    public function destroy(string $id)
    {
        // ? Initial
        $model = FeatureGroup::findOrFail($id);

        // ? Executing
        try {
            $model->delete();
        } catch (\Throwable $th) {
            $this->responseError($th, 'Delete FeatureGroup');
        }

        // ? final
        $this->responseSaved($model->toArray());
    }
}
