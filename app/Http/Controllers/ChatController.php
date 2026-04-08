<?php

namespace App\Http\Controllers;

use App\Ai\Agents\ConversationalAgent;
use App\Models\AgentConversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Ai\Contracts\ConversationStore;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $conversations = AgentConversation::query()
            ->where('user_id', $user->id)
            ->latest('updated_at')
            ->get(['id', 'title', 'updated_at']);

        $currentConversation = $this->resolveConversation(
            userId: $user->id,
            conversationId: $request->string('conversa')->toString() ?: $conversations->first()?->id,
        );

        $messages = $currentConversation
            ? $currentConversation->messages()
                ->orderBy('created_at')
                ->get(['role', 'content', 'created_at'])
                ->map(fn ($message) => [
                    'papel' => $message->role,
                    'texto' => $message->content,
                    'hora' => $message->created_at?->timezone(config('app.timezone'))->format('H:i'),
                ])
                ->values()
                ->all()
            : [];

        return view('chat', [
            'user' => $user,
            'currentConversationId' => $currentConversation?->id,
            'conversations' => $conversations,
            'initialMessages' => $messages,
        ]);
    }

    public function store(Request $request, ConversationStore $conversationStore): JsonResponse
    {
        $dados = $request->validate([
            'mensagem' => ['required', 'string'],
            'conversation_id' => ['nullable', 'string'],
            'historico' => ['sometimes', 'array', 'max:10'],
            'historico.*.papel' => ['required_with:historico', 'in:user,assistant'],
            'historico.*.texto' => ['required_with:historico', 'string'],
        ]);

        $user = $request->user();
        $conversation = $this->resolveConversation($user->id, $dados['conversation_id'] ?? null);
        $agente = new ConversationalAgent();

        if ($conversation) {
            $agente->continue($conversation->id, $user);
        } else {
            $conversationId = $conversationStore->storeConversation(
                $user->id,
                Str::limit($dados['mensagem'], 100, preserveWords: true)
            );

            $agente->continue($conversationId, $user);
        }

        try {
            $resposta = $agente->prompt($dados['mensagem']);

            AgentConversation::query()
                ->where('id', $resposta->conversationId ?? $agente->currentConversation())
                ->update(['updated_at' => now()]);

            return response()->json(
                [
                    'resposta' => (string) $resposta,
                    'conversation_id' => $resposta->conversationId ?? $agente->currentConversation(),
                ],
                200,
                ['Content-Type' => 'application/json; charset=UTF-8'],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        } catch (Throwable $exception) {
            $mensagemErro = mb_strtolower($exception->getMessage());

            $mensagemUsuario = match (true) {
                str_contains($mensagemErro, 'rate limited'),
                str_contains($mensagemErro, 'overloaded') => 'O servico Gemini esta temporariamente ocupado. Tente novamente em alguns segundos.',
                str_contains($mensagemErro, 'could not resolve host') => 'Nao foi possivel contactar o Gemini por falha de rede/DNS. Verifique a internet e tente novamente.',
                str_contains($mensagemErro, 'ssl certificate') => 'Falha de certificado SSL ao contactar o Gemini. Verifique os certificados da maquina.',
                default => 'Nao foi possivel processar sua mensagem agora.',
            };

            Log::error('Erro no agente', [
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            AgentConversation::query()
                ->where('id', $agente->currentConversation())
                ->update(['updated_at' => now()]);

            return response()->json(
                [
                    'message' => $mensagemUsuario,
                    'conversation_id' => $agente->currentConversation(),
                ],
                502,
                ['Content-Type' => 'application/json; charset=UTF-8'],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }
    }

    protected function resolveConversation(int|string $userId, ?string $conversationId): ?AgentConversation
    {
        if (! $conversationId) {
            return null;
        }

        $conversation = AgentConversation::query()
            ->where('id', $conversationId)
            ->where('user_id', $userId)
            ->first();

        if (! $conversation) {
            throw new NotFoundHttpException('Conversa nao encontrada.');
        }

        return $conversation;
    }
}
