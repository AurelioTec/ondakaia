<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IAmue') }} | Criar conta</title>

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
                radial-gradient(circle at top right, rgba(201, 101, 50, 0.16), transparent 28%),
                radial-gradient(circle at bottom left, rgba(10, 61, 104, 0.15), transparent 35%),
                linear-gradient(135deg, #f9f4ec 0%, #efe3d4 45%, #e7dccc 100%);
        }
    </style>
</head>

<body class="min-h-screen font-sans text-brandInk antialiased">
    <main class="mx-auto flex min-h-screen max-w-6xl items-center px-4 py-8">
        <div class="grid w-full gap-6 lg:grid-cols-[1fr_1fr]">
            <section class="rounded-[2rem] border border-white/70 bg-white/70 p-8 shadow-2xl backdrop-blur">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/logo-ondaka.png') }}" alt="Logotipo Ondaka IAmue"
                        class="h-16 w-16 rounded-2xl border border-brandOrange/20 bg-white p-1">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.26em] text-brandOrange">Ondaka IAmue</p>
                        <h1 class="mt-2 text-4xl font-semibold leading-tight text-brandBlue">Crie a sua conta e guarde
                            cada conversa.</h1>
                    </div>
                </div>

                <div class="mt-8 space-y-4 text-sm leading-7 text-brandInk/75">
                    <p>Ao criar conta, o historico das conversas passa a ficar guardado na base de dados e ligado ao seu
                        utilizador.</p>
                    <p>Assim pode regressar mais tarde, abrir a ultima conversa e continuar o estudo sem perder
                        contexto.</p>
                </div>

                <div class="mt-8 rounded-[1.75rem] bg-brandBlue p-6 brandBlueDark">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-brandSand">Vantagens</p>
                    <ul class="mt-4 space-y-3 text-sm text-brandBlueDark">
                        <li>Conta pessoal com sessao protegida.</li>
                        <li>Historico persistente por utilizador.</li>
                        <li>Continuidade das aulas com a IA.</li>
                    </ul>
                </div>
            </section>

            <section class="rounded-[2rem] border border-white/70 bg-white/85 p-8 shadow-2xl backdrop-blur">
                <div class="mb-6">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-brandOrange">Novo utilizador</p>
                    <h2 class="mt-2 text-3xl font-semibold text-brandBlue">Criar conta</h2>
                </div>

                @if ($errors->any())
                    <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-brandInk">Nome</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required
                            autofocus
                            class="w-full rounded-2xl border border-brandBlue/15 bg-brandCream px-4 py-3 text-sm text-brandInk focus:border-brandBlue focus:ring-brandBlue/20">
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-brandInk">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                            class="w-full rounded-2xl border border-brandBlue/15 bg-brandCream px-4 py-3 text-sm text-brandInk focus:border-brandBlue focus:ring-brandBlue/20">
                    </div>

                    <div>
                        <label for="password"
                            class="mb-2 block text-sm font-semibold text-brandInk">Palavra-passe</label>
                        <input id="password" name="password" type="password" required
                            class="w-full rounded-2xl border border-brandBlue/15 bg-brandCream px-4 py-3 text-sm text-brandInk focus:border-brandBlue focus:ring-brandBlue/20">
                    </div>

                    <div>
                        <label for="password_confirmation"
                            class="mb-2 block text-sm font-semibold text-brandInk">Confirmar palavra-passe</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="w-full rounded-2xl border border-brandBlue/15 bg-brandCream px-4 py-3 text-sm text-brandInk focus:border-brandBlue focus:ring-brandBlue/20">
                    </div>

                    <button type="submit"
                        class="inline-flex w-full items-center justify-center rounded-2xl bg-brandOrange px-6 py-3 text-base font-semibold text-black/75 transition hover:bg-brandOrangeSoft focus:outline-none focus:ring-4 focus:ring-brandOrange/25">
                        Criar conta
                    </button>
                </form>

                <p class="mt-6 text-sm text-brandInk/70">
                    Ja tem conta?
                    <a href="{{ route('login') }}"
                        class="font-semibold text-brandBlue hover:text-brandOrange">Entrar</a>
                </p>
            </section>
        </div>
    </main>

    @include('partials.footer')
</body>

</html>
