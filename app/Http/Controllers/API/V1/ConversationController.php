<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $conversations = Conversation::where('user_id', auth()->id())->latest()->get();
        return $this->apiResponse(ConversationResource::collection($conversations), __('messages.success'), 200);
    }

    public function destroy($id)
    {
        $conversation = Conversation::findOrFail($id);

        if ($conversation->user_id !== auth()->id()) {
            return $this->apiResponse(null, __('messages.not_authorized'), 403);
        }

        $conversation->delete();
        return $this->apiResponse(null, __('messages.success'), 200);
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'secretary_id' => 'required|exists:users,id',
        ]);

        $conversation = Conversation::create([
            'user_id' => auth()->id(),
            'secretary_id' => $validated['secretary_id'],
        ]);

        $data = [
            'conversation_id' => $conversation->id,
        ];

        return $this->apiResponse($data, __('messages.success'), 200);
    }
}
