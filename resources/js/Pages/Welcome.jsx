import { Navbar } from "@/components/navbar";

import { Button } from "@/components/ui/button";
import { ArrowRight, CheckCircle } from "lucide-react";

export default function Welcome() {
    return (
        <div className="h-screen flex flex-col">
            <Navbar />
            <main className="flex-grow lg:px-24">
                {/* Hero Section */}
                <section className="w-full py-12 md:py-24 lg:py-32 bg-background">
                    <div className="container px-4 md:px-6">
                        <div className="flex flex-col items-center justify-center gap-10 text-center">
                            <h1 className="text-2xl font-bold tracking-tighter sm:text-3xl lg:text-5xl flex flex-col gap-4 text-gray-500 drop-shadow-lg">
                                Chael Installer
                                <span className="text-purple-600 text-opacity-90 neon-glow-dark">
                                    (A UI Variant of YUI Installer)
                                </span>
                            </h1>
                            <div className="flex flex-col gap-12">
                                <p className="max-w-[700px] text-muted-foreground md:text-xl">
                                    Built with{" "}
                                    <span className="font-semibold text-purple-600">
                                        shadcn/ui
                                    </span>{" "}
                                    for highly customizable and modern UI
                                    components.
                                </p>
                                <p className="bg-gray-900 text-white font-mono px-4 py-3 rounded-lg shadow-md">
                                    composer create-project
                                    chael412/chael-installer
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
            {/* Footer */}
            <footer className="w-full py-2 bg-muted">
                <div className="px-4 md:px-6">
                    <div className="flex flex-col items-center justify-center space-y-4 text-center py-3">
                        <p className="text-sm text-muted-foreground">
                            © 2023 chael412. All rights reserved.
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    );
}
