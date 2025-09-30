<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Maison Mas - Moda d'Avanguardia</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link
            href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700|poppins:300,400,500,600,700&display=swap"
            rel="stylesheet"
        />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gradient-to-br from-black via-neutral-900 to-black text-white font-[\'Poppins\']">
        <div class="relative min-h-screen overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.12),_transparent_45%)]"></div>
                <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-pink-500/30 blur-3xl"></div>
                <div class="absolute -left-24 top-1/2 h-72 w-72 rounded-full bg-amber-400/20 blur-3xl"></div>
            </div>

            <div class="relative z-10 mx-auto flex min-h-screen w-full max-w-7xl flex-col px-6 py-10 lg:px-12">
                <header class="flex items-center justify-between gap-6">
                    <div class="flex items-center gap-3">
                        <span class="flex size-12 items-center justify-center rounded-full bg-white/10 backdrop-blur">
                            <svg class="size-6 text-pink-300" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2c2.485 0 4.5 2.044 4.5 4.565 0 1.289-.523 2.5-1.358 3.371l-3.142 3.206a.5.5 0 0 1-.7 0L8.16 9.936A4.793 4.793 0 0 1 6.5 6.565C6.5 4.044 8.515 2 11 2h1Z" />
                                <path d="M5 21.5a.5.5 0 0 1-.45-.716l2.6-5.2A4.5 4.5 0 0 1 11.331 13H12.5a4.5 4.5 0 0 1 4.181 2.584l2.769 5.872A.5.5 0 0 1 19 22H5a.5.5 0 0 1-.5-.5Z" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-white/60">Maison Mas</p>
                            <p class="font-['Playfair_Display'] text-2xl font-semibold tracking-wide">Eleganza Contemporanea</p>
                        </div>
                    </div>
                    @if (Route::has('login'))
                        <div class="hidden items-center gap-6 text-sm font-medium text-white/70 md:flex">
                            <a class="transition hover:text-white" href="#collezioni">Collezioni</a>
                            <a class="transition hover:text-white" href="#valori">Valori</a>
                            <a class="transition hover:text-white" href="#atelier">Atelier</a>
                            @auth
                                <a href="{{ url('/dashboard') }}" class="rounded-full bg-white px-4 py-2 text-black transition hover:bg-pink-200">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="rounded-full border border-white/30 px-4 py-2 transition hover:border-white hover:bg-white/10">Accedi</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="rounded-full bg-pink-500 px-4 py-2 font-semibold text-white transition hover:bg-pink-400">Registrati</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </header>

                <main class="mt-20 flex flex-1 flex-col gap-24">
                    <section class="grid gap-14 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
                        <div class="space-y-8">
                            <p class="inline-flex items-center gap-3 rounded-full border border-white/10 bg-white/5 px-5 py-2 text-xs uppercase tracking-[0.3em] text-white/70">
                                Nuova stagione · Collezione AI 24/25
                            </p>
                            <h1 class="font-['Playfair_Display'] text-4xl font-semibold leading-tight text-white sm:text-5xl lg:text-6xl">
                                Eleganza che prende vita, tra arte e innovazione.
                            </h1>
                            <p class="max-w-xl text-lg text-white/70">
                                Maison Mas ridisegna i codici del lusso con silhouette fluide, tessuti nobili e dettagli sartoriali. Un universo estetico dedicato ai brand e alle aziende che vivono di stile.
                            </p>
                            <div class="flex flex-wrap gap-4">
                                <a href="#collezioni" class="rounded-full bg-pink-500 px-8 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-pink-400">Scopri le collezioni</a>
                                <a href="#atelier" class="rounded-full border border-white/40 px-8 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white/80 transition hover:border-white hover:text-white">Prenota l'atelier</a>
                            </div>
                            <div class="flex flex-wrap gap-8 pt-4 text-sm text-white/60">
                                <div>
                                    <p class="text-3xl font-semibold text-white">+120</p>
                                    <p>Case history di brand globali</p>
                                </div>
                                <div>
                                    <p class="text-3xl font-semibold text-white">18</p>
                                    <p>Paesi in cui distribuiamo</p>
                                </div>
                                <div>
                                    <p class="text-3xl font-semibold text-white">100%</p>
                                    <p>Materiali certificati e sostenibili</p>
                                </div>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="absolute -inset-6 rounded-3xl bg-gradient-to-br from-pink-500/40 via-fuchsia-500/30 to-indigo-500/20 blur-3xl"></div>
                            <div class="relative overflow-hidden rounded-[36px] border border-white/10 bg-white/5 shadow-[0px_40px_120px_-40px_rgba(236,72,153,0.6)] backdrop-blur">
                                <img src="https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=1200&q=80" alt="Collezione moda" class="h-full w-full object-cover" />
                                <div class="absolute inset-x-0 bottom-0 space-y-4 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-8">
                                    <div class="flex items-center justify-between text-sm text-white/80">
                                        <p>Collezione Signature · 2024</p>
                                        <span class="rounded-full bg-white/10 px-3 py-1 text-xs uppercase tracking-[0.3em]">Limited</span>
                                    </div>
                                    <p class="font-['Playfair_Display'] text-2xl">Luce e Materia</p>
                                    <p class="text-sm text-white/70">Un viaggio tra tessuti reattivi alla luce e ricami realizzati a mano dai nostri atelier europei.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="collezioni" class="space-y-12">
                        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-pink-200">Collezioni Capsule</p>
                                <h2 class="font-['Playfair_Display'] text-4xl font-semibold text-white">Storie visive per brand visionari</h2>
                            </div>
                            <p class="max-w-2xl text-sm text-white/70">
                                Capsule create per valorizzare l'identità dei marchi che seguiamo: linee femminili, maschili e genderless pensate per boutique, sfilate e campagne editoriali.
                            </p>
                        </div>
                        <div class="grid gap-8 lg:grid-cols-3">
                            <article class="group relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 transition hover:-translate-y-2 hover:border-white/30">
                                <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=80" alt="Donna con cappotto couture" class="h-72 w-full object-cover transition duration-500 group-hover:scale-105" />
                                <div class="space-y-4 p-8">
                                    <span class="rounded-full bg-pink-500/20 px-3 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-pink-200">Couture</span>
                                    <h3 class="font-['Playfair_Display'] text-2xl text-white">Riflessi di Venezia</h3>
                                    <p class="text-sm text-white/70">Un omaggio alla laguna con tessuti cangianti e ricami in vetro di Murano.</p>
                                </div>
                            </article>
                            <article class="group relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 transition hover:-translate-y-2 hover:border-white/30">
                                <img src="https://images.unsplash.com/photo-1514996937319-344454492b37?auto=format&fit=crop&w=900&q=80" alt="Modelli in passerella" class="h-72 w-full object-cover transition duration-500 group-hover:scale-105" />
                                <div class="space-y-4 p-8">
                                    <span class="rounded-full bg-indigo-500/20 px-3 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-indigo-200">Runway</span>
                                    <h3 class="font-['Playfair_Display'] text-2xl text-white">Neon Metropolis</h3>
                                    <p class="text-sm text-white/70">Linee architettoniche e pellami soft-touch ispirati ai paesaggi notturni delle città asiatiche.</p>
                                </div>
                            </article>
                            <article class="group relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 transition hover:-translate-y-2 hover:border-white/30">
                                <img src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=80" alt="Fashion editorial" class="h-72 w-full object-cover transition duration-500 group-hover:scale-105" />
                                <div class="space-y-4 p-8">
                                    <span class="rounded-full bg-amber-500/20 px-3 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-amber-200">Editorial</span>
                                    <h3 class="font-['Playfair_Display'] text-2xl text-white">Solar Bloom</h3>
                                    <p class="text-sm text-white/70">Cromie dorate e silhouette fluide per campagne adv di lusso contemporaneo.</p>
                                </div>
                            </article>
                        </div>
                    </section>

                    <section id="valori" class="grid gap-12 rounded-[40px] border border-white/10 bg-white/5 p-10 backdrop-blur lg:grid-cols-2">
                        <div class="space-y-6">
                            <p class="text-xs uppercase tracking-[0.3em] text-pink-200">Il nostro metodo</p>
                            <h2 class="font-['Playfair_Display'] text-4xl font-semibold text-white">Sartorialità digitale e cura artigianale</h2>
                            <p class="text-sm text-white/70">
                                Dall'analisi dei trend all'esecuzione sartoriale, un percorso integrato che combina moodboard digitali, prototipia rapida e lavorazioni manuali d'eccellenza.
                            </p>
                            <div class="grid gap-6 sm:grid-cols-2">
                                <div class="space-y-3 rounded-3xl border border-white/5 bg-white/5 p-6">
                                    <p class="text-xs uppercase tracking-[0.3em] text-white/60">Visione</p>
                                    <p class="text-lg text-white">Creative Lab</p>
                                    <p class="text-sm text-white/60">Ricerca, direzione artistica, concept per sfilate e shooting.</p>
                                </div>
                                <div class="space-y-3 rounded-3xl border border-white/5 bg-white/5 p-6">
                                    <p class="text-xs uppercase tracking-[0.3em] text-white/60">Materia</p>
                                    <p class="text-lg text-white">Textile Hub</p>
                                    <p class="text-sm text-white/60">Materiali innovativi certificati FSC®, GOTS e low impact.</p>
                                </div>
                                <div class="space-y-3 rounded-3xl border border-white/5 bg-white/5 p-6">
                                    <p class="text-xs uppercase tracking-[0.3em] text-white/60">Esperienza</p>
                                    <p class="text-lg text-white">Retail Stories</p>
                                    <p class="text-sm text-white/60">Visual merchandising e customer journey tailor-made.</p>
                                </div>
                                <div class="space-y-3 rounded-3xl border border-white/5 bg-white/5 p-6">
                                    <p class="text-xs uppercase tracking-[0.3em] text-white/60">Sostenibilità</p>
                                    <p class="text-lg text-white">Circular Atelier</p>
                                    <p class="text-sm text-white/60">Upcycling e tracciabilità blockchain per la filiera.</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-8">
                            <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-white/10 via-white/5 to-white/0 p-8 shadow-[0_40px_120px_-50px_rgba(250,250,250,0.4)]">
                                <p class="text-sm uppercase tracking-[0.3em] text-pink-100">Voce ai partner</p>
                                <blockquote class="mt-6 font-['Playfair_Display'] text-2xl leading-relaxed text-white">
                                    «Con Maison Mas abbiamo rivoluzionato il modo di raccontare il nostro brand. Il livello di cura e innovazione è semplicemente straordinario.»
                                </blockquote>
                                <div class="mt-6 flex items-center gap-4 text-sm text-white/70">
                                    <img src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=200&q=60" alt="Creative director" class="size-12 rounded-full object-cover" />
                                    <div>
                                        <p class="text-white">Elena Marchesi</p>
                                        <p>Creative Director · Aurora Atelier</p>
                                    </div>
                                </div>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                                <p class="text-xs uppercase tracking-[0.3em] text-pink-200">Certificazioni</p>
                                <div class="mt-4 grid gap-4 text-sm text-white/70 sm:grid-cols-2">
                                    <p class="rounded-full border border-white/10 px-4 py-2 text-center">ISO 9001 · Quality Management</p>
                                    <p class="rounded-full border border-white/10 px-4 py-2 text-center">Fair Trade Textile Partner</p>
                                    <p class="rounded-full border border-white/10 px-4 py-2 text-center">Carbon Neutral Certified</p>
                                    <p class="rounded-full border border-white/10 px-4 py-2 text-center">Made in Italy Excellence</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="atelier" class="grid gap-10 lg:grid-cols-[0.75fr_1.25fr]">
                        <div class="space-y-6">
                            <p class="text-xs uppercase tracking-[0.3em] text-pink-200">Atelier Experience</p>
                            <h2 class="font-['Playfair_Display'] text-4xl font-semibold text-white">Un hub immersivo nel cuore di Milano</h2>
                            <p class="text-sm text-white/70">
                                Prenota un appuntamento esclusivo nel nostro atelier per conoscere collezioni private, sviluppare capsule collaborative o creare uniformi aziendali che incarnino la vostra identità.
                            </p>
                            <ul class="space-y-4 text-sm text-white/70">
                                <li class="flex items-start gap-3"><span class="mt-1 size-2 rounded-full bg-pink-400"></span> Styling board personalizzate e rendering 3D in tempo reale.</li>
                                <li class="flex items-start gap-3"><span class="mt-1 size-2 rounded-full bg-pink-400"></span> Campionario tessile con più di 800 referenze premium.</li>
                                <li class="flex items-start gap-3"><span class="mt-1 size-2 rounded-full bg-pink-400"></span> Lounge dedicata a press day, eventi privati e masterclass.</li>
                            </ul>
                            <a href="mailto:atelier@maisonmas.com" class="inline-flex items-center gap-3 rounded-full bg-white px-8 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-black transition hover:bg-pink-100">
                                Prenota la tua esperienza
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                    <path d="m13 6 6 6-6 6" />
                                </svg>
                            </a>
                        </div>
                        <div class="relative overflow-hidden rounded-[36px] border border-white/10 bg-white/5">
                            <img src="https://images.unsplash.com/photo-1529338296731-c4280a44fc47?auto=format&fit=crop&w=1400&q=80" alt="Atelier" class="h-full w-full object-cover" />
                            <div class="absolute inset-0 bg-gradient-to-tr from-black/70 via-black/30 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 space-y-4 p-10">
                                <span class="rounded-full bg-white/10 px-4 py-1 text-xs uppercase tracking-[0.3em] text-white/80">Private Viewing</span>
                                <p class="max-w-md font-['Playfair_Display'] text-3xl leading-snug text-white">Un luogo dove i vostri concept prendono forma, guidati dai nostri artigiani e fashion strategist.</p>
                            </div>
                        </div>
                    </section>
                </main>

                <footer class="mt-24 border-t border-white/10 pt-10 text-sm text-white/60">
                    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                        <p>&copy; {{ date('Y') }} Maison Mas. Tutti i diritti riservati.</p>
                        <div class="flex flex-wrap items-center gap-4">
                            <a href="mailto:press@maisonmas.com" class="transition hover:text-white">Press</a>
                            <a href="mailto:partnership@maisonmas.com" class="transition hover:text-white">Partnership</a>
                            <a href="https://instagram.com" class="transition hover:text-white" target="_blank" rel="noreferrer">Instagram</a>
                            <a href="https://www.linkedin.com" class="transition hover:text-white" target="_blank" rel="noreferrer">LinkedIn</a>
                        </div>
                        <p class="text-xs text-white/40">Laravel v{{ Illuminate\Foundation\Application::VERSION }} · PHP v{{ PHP_VERSION }}</p>
                    </div>
                </footer>
            </div>
        </div>
    </body>
</html>
