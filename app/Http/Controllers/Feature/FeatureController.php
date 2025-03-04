<?php

namespace App\Http\Controllers\Feature;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Feature;

class FeatureController extends Controller
{
    // ========================================>
    // ## Display a listing of the resource.
    // ========================================>
    public function index(Request $request)
    {   
        // ? Initial params
        $params = $this->getParams($request);

        // ? Begin
        $query = Feature::query()->with(['accesses', 'group'])
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
            "group_feature_id" => "nullable|number",
            "code" => "required|string|max:3|unique:features,code",
            "name" => "required|string|max:20|unique:features,name",
            "description" => "nullable|string|max:255",
        ]);

        // ? Initial
        DB::beginTransaction();
        $model = new Feature();

        // ? Dump data
        $model->dumpField($request);

        // ? Executing
        try {
            $model->save();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->responseError($th, 'Create Feature');
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
        $model = Feature::findOrFail($id);

        // ? Validate request
        $this->validation($request->all(), [
            "group_feature_id" => "nullable|number",
            "code" => "required|string|max:3|unique:features,code",
            "name" => "required|string|max:20|unique:features,name",
            "description" => "nullable|string|max:255",
        ]);

        // ? Dump data
        $model->dumpField($request);

        // ? Executing
        try {
            $model->save();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->responseError($th, 'Update Feature');
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
        $model = Feature::findOrFail($id);

        // ? Executing
        try {
            $model->delete();
        } catch (\Throwable $th) {
            $this->responseError($th, 'Delete Feature');
        }

        // ? final
        $this->responseSaved($model->toArray());
    }
}
