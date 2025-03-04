<?php

namespace App\Http\Controllers\Feature;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\FeatureAccess;

class FeatureAccessController extends Controller
{
    // ========================================>
    // ## Display a listing of the resource.
    // ========================================>
    public function index(Request $request)
    {   
        // ? Initial params
        $params = $this->getParams($request);

        // ? Begin
        $query = FeatureAccess::query()->with(['feature'])
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
            "feature_id" => "nullable|number",
            "code" => "required|string|unique:feature_accesses,code",
            "name" => "required|string",
        ]);

        // ? Initial
        DB::beginTransaction();
        $model = new FeatureAccess();

        // ? Dump data
        $model->dumpField($request);

        // ? Executing
        try {
            $model->save();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->responseError($th, 'Create FeatureAccess');
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
        $model = FeatureAccess::findOrFail($id);

        // ? Validate request
        $this->validation($request->all(), [
            "feature_id" => "nullable|number",
            "code" => "required|string|unique:feature_accesses,code",
            "name" => "required|string",
        ]);

        // ? Dump data
        $model->dumpField($request);

        // ? Executing
        try {
            $model->save();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->responseError($th, 'Update FeatureAccess');
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
        $model = FeatureAccess::findOrFail($id);

        // ? Executing
        try {
            $model->delete();
        } catch (\Throwable $th) {
            $this->responseError($th, 'Delete FeatureAccess');
        }

        // ? final
        $this->responseSaved($model->toArray());
    }
}
