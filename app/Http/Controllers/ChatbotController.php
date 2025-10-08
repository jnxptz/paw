<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatLog;
use App\Models\ChatSession;
use Illuminate\Support\Facades\Auth;
use App\Services\DialogflowService;

class ChatbotController extends Controller
{
    protected $dialogflow;

    public function __construct(DialogflowService $dialogflow)
    {
        $this->dialogflow = $dialogflow;
    }

    public function index()
    {
        return view('chat.index');
    }

    public function send(Request $request)
    {
         \Log::info('Chatbot send() triggered', $request->all()); 
        $start = microtime(true);
        $userMessage = trim($request->input('message'));
        $userId = Auth::id();

        if (empty($userMessage)) {
            return response()->json([
                'reply' => "⚠️ Please enter a message.",
                'time' => 0,
                'session_id' => null,
            ]);
        }

        // Get or create a chat session
        $session = ChatSession::firstOrCreate(
            ['user_id' => $userId],
            ['session_name' => 'Session ' . now()->format('M d, Y H:i')]
        );

        $reply = "Sorry, I didn’t understand that.";
        $category = 'General';

        try {
            // Call Dialogflow service
            $result = $this->dialogflow->detectIntent($session->id, $userMessage);

            if ($result && isset($result->fulfillmentText)) {
                $reply = $result->fulfillmentText;
            }

            if ($result && isset($result->intentDisplayName)) {
                $category = $result->intentDisplayName;
            }

        } catch (\Exception $e) {
            $reply = "⚠️ Error communicating with Dialogflow: " . $e->getMessage();
            $category = 'Error';
        }

        $responseTime = microtime(true) - $start;

        // Save chat log
        ChatLog::create([
            'user_id' => $userId,
            'chat_session_id' => $session->id,
            'question' => $userMessage,
            'answer' => $reply,
            'category' => $category,
            'response_time' => $responseTime,
        ]);

        return response()->json([
            'reply' => $reply,
            'time' => round($responseTime, 2),
            'session_id' => $session->id,
        ]);
        
    }

    public function showConversation($sessionId)
    {
        $session = ChatSession::with('chatLogs.user')->findOrFail($sessionId);

        if ($session->user_id != auth()->id()) {
            abort(403);
        }

        return view('chatbot.index', [
            'conversation' => $session->chatLogs
        ]);
    }
}
