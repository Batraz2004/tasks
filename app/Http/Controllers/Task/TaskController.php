<?php

namespace App\Http\Controllers\Task;

use App\Enums\TaskStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMessageEmail;
use Throwable;

class TaskController extends Controller
{
    public function create(TaskRequest $request)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            $task = $user
                ->tasks()
                ->create($request->getData());

            $task->load('parent');
            $task->addMedia($request->file_image)
                ->toMediaCollection('task_image');

            Mail::to(config('services.email.info'))?->send(new SendMessageEmail($user, "таска создана"));

            return response()->json([
                'data' => TaskResource::make($task),
            ], 200);
        } catch (Throwable $th) {
            Log::debug("произошла ошибка:" . $th->getMessage() . " строка:" . $th->getLine());

            return response()->json([
                'data' => 'проищошла ошибка',
                'code' => $th->getCode(),
            ], $th->getCode());
        }
    }

    public function update(TaskRequest $request, $id)
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $task = $user
                ->tasks()
                ->where('id', $id)
                ->update($request->getData());

            $task = $user->tasks()->where('id', $id)->first();
            $task->load('parent');

            if ($task->status->value == TaskStatusEnum::done->value) {
                $task->ending_at = Carbon::now();
                $task->save();
            }

            return response()->json([
                'data' => TaskResource::make($task),
            ], 200);
        } catch (Throwable $th) {
            Log::debug("произошла ошибка:" . $th->getMessage() . " строка:" . $th->getLine());

            return response()->json([
                'data' => 'проищошла ошибка',
                'code' => $th->getCode(),
            ], $th->getCode());
        }
    }

    public function list()
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $tasks = $user?->tasks;

            return response()->json([
                'data' => TaskResource::collection($tasks),
                'code' => 200,
            ], 200);
        } catch (Throwable $th) {
            Log::debug("произошла ошибка:" . $th->getMessage() . " строка:" . $th->getLine());

            return response()->json([
                'data' => 'проищошла ошибка',
                'code' => $th->getCode(),
            ], $th->getCode());
        }
    }

    public function getById($id)
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $task = $user?->tasks()->getQuery()->firstWhere(['id' => $id]);

            return response()->json([
                'data' => TaskResource::make($task),
                'code' => 200,
            ], 200);
        } catch (Throwable $th) {
            Log::debug("произошла ошибка:" . $th->getMessage() . " строка:" . $th->getLine());

            return response()->json([
                'data' => 'проищошла ошибка',
                'code' => $th->getCode(),
            ], $th->getCode());
        }
    }

    public function deleteById($id)
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $task = $user?->tasks()->getQuery()->where('id', $id)->delete();

            return response()->json([
                'data' => 'запись удалена',
                'code' => 200,
            ], 200);
        } catch (Throwable $th) {
            Log::debug("произошла ошибка:" . $th->getMessage() . " строка:" . $th->getLine());

            return response()->json([
                'data' => 'проищошла ошибка',
                'code' => $th->getCode(),
            ], $th->getCode());
        }
    }
}
