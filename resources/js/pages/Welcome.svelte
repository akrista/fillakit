<script lang="ts">
    import { page } from '@inertiajs/svelte';
    import { Button } from '@/components/ui/button';
    import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
    import { Badge } from '@/components/ui/badge';
    import { BookOpen, Rocket, Zap, Shield, Code, ArrowRight } from 'lucide-svelte';

    let panelRoute = $derived($page.props.panelRoute || 'admin');
    let demoUrl = $derived(`/${panelRoute}`);
</script>

<svelte:head>
    <title>Welcome - Fillakit</title>
    <link rel="preconnect" href="https://rsms.me/" />
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
</svelte:head>

<div class="relative min-h-screen overflow-hidden bg-background">
    <!-- Background Pattern -->
    <div
        class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_0%,#000_70%,transparent_110%)]"
    ></div>

    <!-- Gradient Overlay -->
    <div
        class="absolute inset-0 bg-gradient-to-b from-background via-background/95 to-background"
    ></div>

    <div class="relative z-10 flex min-h-screen flex-col">
        <!-- Header -->
        <header class="container mx-auto px-6 pt-8 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                        <Rocket class="h-5 w-5" />
                    </div>
                    <span class="text-xl font-bold">Fillakit</span>
                </div>
                <Badge variant="outline" class="hidden sm:flex">
                    <Rocket class="mr-1 h-3 w-3" />
                    Starter Kit
                </Badge>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto flex flex-1 flex-col items-center justify-center px-6 py-16 lg:px-8">
            <div class="flex w-full max-w-6xl flex-col items-center gap-12">
                <!-- Hero Section -->
                <div class="flex flex-col items-center gap-6 text-center">
                    <Badge variant="secondary" class="mb-2">
                        <Zap class="mr-1 h-3 w-3" />
                        Rapid Development
                    </Badge>
                    <h1 class="text-5xl font-bold tracking-tight lg:text-6xl">
                        Build Fast, Build Smart
                        <span class="block bg-gradient-to-r from-primary to-destructive bg-clip-text text-transparent">
                            with Filament
                        </span>
                    </h1>
                    <p class="max-w-2xl text-lg text-muted-foreground lg:text-xl">
                        A powerful Laravel starter kit designed to accelerate your development. Create robust and scalable applications from day one with Filament.
                    </p>
                    <div class="flex flex-col gap-4 sm:flex-row">
                        <Button href={demoUrl} size="lg" class="group">
                            View Demo
                            <ArrowRight class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1" />
                        </Button>
                        <Button href="/docs" variant="outline" size="lg" class="group">
                            <BookOpen class="mr-2 h-4 w-4" />
                            View Documentation
                        </Button>
                    </div>
                </div>

                <!-- Features Grid -->
                <div class="grid w-full gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <Card class="border-border/50 bg-card/50 backdrop-blur-sm">
                        <CardHeader>
                            <div class="mb-2 flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                                <Rocket class="h-5 w-5 text-primary" />
                            </div>
                            <CardTitle>Quick Start</CardTitle>
                            <CardDescription>
                                Get up and running in minutes with a pre-configured setup that includes authentication, permissions, and more.
                            </CardDescription>
                        </CardHeader>
                    </Card>

                    <Card class="border-border/50 bg-card/50 backdrop-blur-sm">
                        <CardHeader>
                            <div class="mb-2 flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                                <Zap class="h-5 w-5 text-primary" />
                            </div>
                            <CardTitle>Filament Powered</CardTitle>
                            <CardDescription>
                                Built on Filament v4, the most powerful admin panel framework for Laravel. Create beautiful interfaces effortlessly.
                            </CardDescription>
                        </CardHeader>
                    </Card>

                    <Card class="border-border/50 bg-card/50 backdrop-blur-sm sm:col-span-2 lg:col-span-1">
                        <CardHeader>
                            <div class="mb-2 flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                                <Shield class="h-5 w-5 text-primary" />
                            </div>
                            <CardTitle>Secure by Default</CardTitle>
                            <CardDescription>
                                Includes Spatie Roles & Permissions, multi-factor authentication, and security best practices out of the box.
                            </CardDescription>
                        </CardHeader>
                    </Card>

                    <Card class="border-border/50 bg-card/50 backdrop-blur-sm sm:col-span-2 lg:col-span-1">
                        <CardHeader>
                            <div class="mb-2 flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                                <Code class="h-5 w-5 text-primary" />
                            </div>
                            <CardTitle>Modern Stack</CardTitle>
                            <CardDescription>
                                Laravel 12, Octane, Reverb, Horizon, and Inertia.js. Everything you need for modern, performant applications.
                            </CardDescription>
                        </CardHeader>
                    </Card>

                    <Card class="border-border/50 bg-card/50 backdrop-blur-sm">
                        <CardHeader>
                            <div class="mb-2 flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                                <BookOpen class="h-5 w-5 text-primary" />
                            </div>
                            <CardTitle>Documentation</CardTitle>
                            <CardDescription>
                                Comprehensive documentation powered by Converge. Learn quickly and build confidently.
                            </CardDescription>
                        </CardHeader>
                    </Card>

                    <Card class="border-border/50 bg-card/50 backdrop-blur-sm">
                        <CardHeader>
                            <div class="mb-2 flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                                <Zap class="h-5 w-5 text-primary" />
                            </div>
                            <CardTitle>Production Ready</CardTitle>
                            <CardDescription>
                                Includes everything needed for production: queue workers, caching, monitoring, and more.
                            </CardDescription>
                        </CardHeader>
                    </Card>
                </div>

                <!-- CTA Section -->
                <div class="w-full">
                    <Card class="border-primary/20 bg-gradient-to-br from-primary/5 to-destructive/5">
                        <CardContent class="px-6 py-12 text-center">
                            <h2 class="mb-4 text-3xl font-bold">Ready to Build?</h2>
                            <p class="mb-6 text-muted-foreground">
                                Start your next project with Fillakit and ship faster than ever.
                            </p>
                            <div class="flex flex-col gap-4 sm:flex-row sm:justify-center">
                                <Button href={demoUrl} size="lg" variant="default">
                                    Explore Demo
                                    <ArrowRight class="ml-2 h-4 w-4" />
                                </Button>
                                <Button href="/docs" size="lg" variant="outline">
                                    <BookOpen class="mr-2 h-4 w-4" />
                                    Read Docs
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="container mx-auto border-t border-border/50 px-6 py-8 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <p class="text-sm text-muted-foreground">
                    © 2025 Fillakit. Built with Laravel & Filament.
                </p>
                <div class="flex gap-6 text-sm text-muted-foreground">
                    <a href="/docs" class="hover:text-foreground transition-colors">Documentation</a>
                    <a href="https://github.com/akrista/fillakit" target="_blank" rel="noopener noreferrer" class="hover:text-foreground transition-colors">GitHub</a>
                </div>
            </div>
        </footer>
    </div>
</div>
