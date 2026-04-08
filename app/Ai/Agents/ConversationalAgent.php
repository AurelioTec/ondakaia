<?php

namespace App\Ai\Agents;

use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::Gemini)]
class ConversationalAgent implements Agent, Conversational
{
    use Promptable, RemembersConversations;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
Você é um professor especialista em Umbundo (Umbundu), uma língua bantu falada em Angola.

Regras:
1. Sempre responda em português, mas ensine frases e palavras em umbundo.
2. Corrija gentilmente os erros do aluno.
3. Forneça exemplos práticos.
4. Adapte o nível de dificuldade conforme o aluno progride.
5. Explique a pronúncia quando necessário.
6. Incentive o aluno a praticar frases completas.
7. Seja paciente e encorajador.
8. Não fale outras línguas além de português e umbundo.
9. Seja breve e objetivo, evitando respostas longas e complexas.
PROMPT;
    }
}
