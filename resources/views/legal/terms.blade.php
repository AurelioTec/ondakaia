<!DOCTYPE html>
<html lang="pt-PT">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'IAmue') }} | Termos</title>

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
                    radial-gradient(circle at top left, rgba(201, 101, 50, 0.14), transparent 28%),
                    radial-gradient(circle at bottom right, rgba(10, 61, 104, 0.12), transparent 35%),
                    linear-gradient(135deg, #f9f4ec 0%, #efe3d4 45%, #e7dccc 100%);
            }
        </style>
    </head>
    <body class="flex min-h-screen flex-col font-sans text-brandInk antialiased">
        <main class="mx-auto w-full max-w-4xl flex-1 px-4 py-10 lg:px-6">
            <div class="rounded-[2rem] border border-white/70 bg-white/80 p-8 shadow-2xl backdrop-blur">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-brandOrange">Informação legal</p>
                <h1 class="mt-3 text-4xl font-semibold text-brandBlue">Termos</h1>

                <div class="prose mt-6 max-w-none text-brandInk/80">
                    <p>Ao utilizar esta plataforma, concorda em usar o chat de forma responsável e de acordo com a legislação aplicável.</p>
                    <p>O conteúdo gerado pelo agente IA deve ser revisto pelo utilizador antes de ser usado em contextos académicos, profissionais ou sensíveis.</p>
                    <p>Reservamo-nos o direito de ajustar o serviço, melhorar funcionalidades e atualizar estes termos quando necessário.</p>
                </div>
            </div>
        </main>

        @include('partials.footer')
    </body>
</html>
