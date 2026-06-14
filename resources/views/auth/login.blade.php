<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ONDAKA') }} | Entrar</title>

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
                        brandBlueDark: '#062B49',
                        brandOrange: '#C96532',
                        brandOrangeSoft: '#E38B59',
                        brandCream: '#F8F3EA',
                        brandSand: '#EEE2D2',
                        brandInk: '#1D3448',
                    },
                },
            },
        };
    </script>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <style>
        body {
            background:
                radial-gradient(circle at top left, rgba(201, 101, 50, 0.18), transparent 28%),
                radial-gradient(circle at bottom right, rgba(10, 61, 104, 0.16), transparent 34%),
                linear-gradient(135deg, #f9f4ec 0%, #efe3d4 45%, #e7dccc 100%);
        }
    </style>
</head>

<body class="min-h-screen font-sans text-brandInk antialiased">
    <main class="mx-auto flex min-h-screen max-w-6xl items-center px-4 py-8">
        <div class="grid w-full gap-6 lg:grid-cols-[1.05fr_0.95fr]">
            <section class="rounded-[2rem] border border-white/70 bg-white/70 p-8 shadow-2xl backdrop-blur">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/logo-ondaka.png') }}" alt="Logotipo Ondaka IAmue"
                        class="h-16 w-16 rounded-2x1 border border-brandOrange/20 bg-white p-1">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.26em] text-brandOrange">Ondaka IAmue</p>
                        <h1 class="mt-2 text-4xl font-semibold leading-tight text-brandBlue">Entre para continuar as
                            suas conversas.</h1>
                    </div>
                </div>

                <p class="mt-6 max-w-2xl text-base leading-8 text-brandInk/75">
                    Guarde o historico das perguntas e respostas na base de dados, retome aulas anteriores e acompanhe a
                    sua pratica em Umbundo em qualquer sessao.
                </p>

                <div class="mt-8 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-3xl bg-brandCream p-4">
                        <p class="text-sm font-semibold text-brandBlue">Historico persistente</p>
                        <p class="mt-2 text-sm text-brandBlue">As conversas ficam ligadas a sua conta.</p>
                    </div>
                    <div class="rounded-3xl bg-brandBlue p-4 text-brandOrange">
                        <p class="text-sm font-semibold">Continuidade</p>
                        <p class="mt-2 text-sm text-brandOrange/80">Retome a ultima conversa sem perder contexto.</p>
                    </div>
                    <div class="rounded-3xl bg-brandOrange p-4 text-brandOrange">
                        <p class="text-sm font-semibold">Aprendizagem guiada</p>
                        <p class="mt-2 text-sm text-brandOrange/85">Pratique com seguranca e progresso real.</p>
                    </div>
                </div>
            </section>

            <section class="rounded-[2rem] border border-white/70 bg-white/85 p-8 shadow-2xl backdrop-blur">
                <div class="mb-6">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-brandOrange">Autenticacao</p>
                    <h2 class="mt-2 text-3xl font-semibold text-black">Entrar</h2>
                    <p class="mt-3 text-sm leading-7 text-brandInk/70">
                        Use o seu email e password para abrir o chat com historico guardado.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-brandInk">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                            autofocus
                            class="w-full rounded-2xl border border-brandBlue/15 bg-brandCream px-4 py-3 text-sm text-brandInk focus:border-brandBlue focus:ring-brandBlue/20">
                    </div>

                    <div>
                        <label for="password"
                            class="mb-2 block text-sm font-semibold text-brandInk">Palavra passe</label>
                        <input id="password" name="password" type="password" required
                            class="w-full rounded-2xl border border-brandBlue/15 bg-brandCream px-4 py-3 text-sm text-brandInk focus:border-brandBlue focus:ring-brandBlue/20">
                    </div>

                    <label
                        class="flex items-center gap-3 rounded-2xl border border-brandBlue/10 bg-brandCream/70 px-4 py-3 text-sm text-brandInk/80">
                        <input type="checkbox" name="remember"
                            class="rounded border-brandBlue/25 text-brandBlue focus:ring-brandBlue/20">
                        Manter a sessao iniciada neste dispositivo
                    </label>

                    <button type="submit"
                        class="inline-flex w-full items-center justify-center rounded-2xl bg-brandOrange px-6 py-3 text-base font-semibold text-black transition hover:bg-white/20 focus:outline focus:ring-4 focus:ring-black/10">
                        Entrar no chat
                    </button>
                </form>

                <p class="mt-6 text-sm text-brandInk/70">
                    Ainda nao tem conta?
                    <a href="{{ route('register') }}" class="font-semibold text-brandBlue hover:text-brandOrange">Criar
                        conta agora</a>
                </p>
            </section>
        </div>
    </main>

    @include('partials.footer')
</body>

</html>
