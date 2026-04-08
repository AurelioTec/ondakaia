<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'IAmue') }} | Privacidade</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        },
                        colors: {
                            brandBlue: '#0A3D68',
                            brandOrange: '#C96532',
                            brandCream: '#F8F3EA',
                            brandInk: '#1D3448',
                        },
                    },
                },
            };
        </script>
        <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
        <style>
            body {
                background:
                    radial-gradient(circle at top right, rgba(201, 101, 50, 0.14), transparent 28%),
                    radial-gradient(circle at bottom left, rgba(10, 61, 104, 0.12), transparent 35%),
                    linear-gradient(135deg, #f9f4ec 0%, #efe3d4 45%, #e7dccc 100%);
            }
        </style>
    </head>
    <body class="min-h-screen font-sans text-brandInk antialiased">
        <main class="mx-auto max-w-4xl px-4 py-10 lg:px-6">
            <div class="rounded-[2rem] border border-white/70 bg-white/80 p-8 shadow-2xl backdrop-blur">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-brandOrange">Informacao legal</p>
                <h1 class="mt-3 text-4xl font-semibold text-brandBlue">Privacidade</h1>

                <div class="prose mt-6 max-w-none text-brandInk/80">
                    <p>Os dados introduzidos no chat podem ser armazenados para manter o historico das conversas associado a sua conta.</p>
                    <p>As informacoes de autenticacao e historico sao usadas apenas para fornecer acesso, continuidade da conversa e melhoria da experiencia da plataforma.</p>
                    <p>Recomendamos que nao partilhe dados sensiveis ou confidenciais nas mensagens enviadas ao agente.</p>
                </div>
            </div>
        </main>

        @include('partials.footer')
    </body>
</html>
