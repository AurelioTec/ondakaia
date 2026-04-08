<?php

namespace Tests\Feature;

use App\Ai\Agents\ConversationalAgent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DB::table('agent_conversation_messages')->delete();
        DB::table('agent_conversations')->delete();
        DB::table('users')->delete();
    }

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/')
            ->assertRedirect(route('login'));
    }

    public function test_the_footer_links_and_legal_pages_are_available(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSeeText('Termos')
            ->assertSeeText('Privacidade')
            ->assertSeeText('FA-DEV');

        $this->get('/termos')
            ->assertOk()
            ->assertSeeText('Termos');

        $this->get('/privacidade')
            ->assertOk()
            ->assertSeeText('Privacidade');
    }

    public function test_users_can_register_and_access_the_chat_page(): void
    {
        $response = $this->post('/register', [
            'name' => 'Aluno Teste',
            'email' => 'aluno@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('chat.index'));
        $this->assertAuthenticated();

        $this->get('/')
            ->assertOk()
            ->assertSeeText('Sala de conversa')
            ->assertSee('logo-ondaka.png')
            ->assertSee('id="send-button"', false);
    }

    public function test_the_conversation_route_returns_an_agent_response_and_stores_the_history(): void
    {
        $user = User::factory()->create();

        ConversationalAgent::fake(['Ola! Em que posso ajudar?']);

        $response = $this->actingAs($user)->postJson('/conversar', [
            'mensagem' => 'Ola',
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'resposta' => 'Ola! Em que posso ajudar?',
            ])
            ->assertJsonStructure(['conversation_id']);

        $conversationId = (string) $response->json('conversation_id');

        $this->assertNotSame('', $conversationId);

        $this->assertDatabaseHas('agent_conversations', [
            'id' => $conversationId,
            'user_id' => $user->id,
            'title' => 'Ola',
        ]);

        $this->assertDatabaseHas('agent_conversation_messages', [
            'conversation_id' => $conversationId,
            'user_id' => $user->id,
            'role' => 'user',
            'content' => 'Ola',
        ]);

        $this->assertDatabaseHas('agent_conversation_messages', [
            'conversation_id' => $conversationId,
            'user_id' => $user->id,
            'role' => 'assistant',
            'content' => 'Ola! Em que posso ajudar?',
        ]);
    }

    public function test_authenticated_users_can_continue_an_existing_conversation(): void
    {
        $user = User::factory()->create();

        ConversationalAgent::fake([
            'Primeira resposta',
            'Segunda resposta',
        ]);

        $primeiraResposta = $this->actingAs($user)->postJson('/conversar', [
            'mensagem' => 'Primeira pergunta',
        ]);

        $conversationId = (string) $primeiraResposta->json('conversation_id');

        $segundaResposta = $this->actingAs($user)->postJson('/conversar', [
            'mensagem' => 'Segunda pergunta',
            'conversation_id' => $conversationId,
        ]);

        $segundaResposta
            ->assertOk()
            ->assertJson([
                'resposta' => 'Segunda resposta',
                'conversation_id' => $conversationId,
            ]);

        $this->assertDatabaseCount('agent_conversation_messages', 4);
    }

    public function test_users_cannot_access_another_users_conversation(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        ConversationalAgent::fake(['Resposta privada']);

        $response = $this->actingAs($owner)->postJson('/conversar', [
            'mensagem' => 'Conversa privada',
        ]);

        $conversationId = (string) $response->json('conversation_id');

        $this->actingAs($intruder)->postJson('/conversar', [
            'mensagem' => 'Tentativa indevida',
            'conversation_id' => $conversationId,
        ])->assertNotFound();
    }

    public function test_the_conversation_route_handles_provider_failures_for_authenticated_users(): void
    {
        $user = User::factory()->create();

        ConversationalAgent::fake([
            fn () => throw new RuntimeException('Falha simulada'),
        ]);

        $response = $this->actingAs($user)->postJson('/conversar', [
            'mensagem' => 'Teste',
        ]);

        $response
            ->assertStatus(502)
            ->assertJsonStructure(['message', 'conversation_id']);
    }
}
