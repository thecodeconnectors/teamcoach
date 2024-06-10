<?php

namespace App\Modules\Settings\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingRequest;
use App\Modules\Settings\Http\Resources\SettingResource;
use App\Repositories\SettingsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(protected SettingsRepository $repository) { }

    public function index(Request $request): JsonResponse
    {
        $settings = $this->repository->setRequest($request)->withDefaults();

        return response()->json(['data' => $settings]);
    }

    public function store(UpdateSettingRequest $request, string $key): SettingResource
    {
        $setting = $this->repository->setRequest($request)->store($key, $request->validated('value'));

        return new SettingResource($setting);
    }
}
